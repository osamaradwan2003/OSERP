<?php

namespace App\Libraries;

use App\Models\Item;
use App\Models\Stock_location;
use App\Models\Stock_transfer;
use CodeIgniter\Session\Session;
use Config\OSPOS;

/**
 * Transfer Library
 *
 * Library with utilities to manage stock transfers between locations
 */
class Transfer_lib
{
    private Item $item;
    private Stock_location $stock_location;
    private Stock_transfer $stock_transfer;
    private Session $session;

    public function __construct()
    {
        $this->item = model(Item::class);
        $this->stock_location = model(Stock_location::class);
        $this->stock_transfer = model(Stock_transfer::class);
        $this->session = session();
    }

    /**
     * Get the transfer cart
     *
     * @return array
     */
    public function get_cart(): array
    {
        if (!$this->session->get('transfer_cart')) {
            $this->set_cart([]);
        }

        return $this->session->get('transfer_cart');
    }

    /**
     * Set the transfer cart
     *
     * @param array $cart_data
     * @return void
     */
    public function set_cart(array $cart_data): void
    {
        $this->session->set('transfer_cart', $cart_data);
    }

    /**
     * Empty the transfer cart
     *
     * @return void
     */
    public function empty_cart(): void
    {
        $this->session->remove('transfer_cart');
    }

    /**
     * Get source location
     *
     * @return int
     */
    public function get_source_location(): int
    {
        if (!$this->session->get('transfer_source_location')) {
            $this->set_source_location($this->stock_location->get_default_location_id('transfers'));
        }

        return $this->session->get('transfer_source_location');
    }

    /**
     * Set source location
     *
     * @param int $location_id
     * @return void
     */
    public function set_source_location(int $location_id): void
    {
        $this->session->set('transfer_source_location', $location_id);
    }

    /**
     * Get destination location
     *
     * @return int
     */
    public function get_destination_location(): int
    {
        if (!$this->session->get('transfer_destination_location')) {
            // Get all available locations
            $locations = $this->stock_location->get_all_locations();
            $source_location = $this->get_source_location();
            
            // Find first location that's not the source
            $destination = $source_location;
            foreach ($locations as $location) {
                if ($location->location_id != $source_location) {
                    $destination = $location->location_id;
                    break;
                }
            }
            
            $this->set_destination_location($destination);
        }

        return $this->session->get('transfer_destination_location');
    }

    /**
     * Set destination location
     *
     * @param int $location_id
     * @return void
     */
    public function set_destination_location(int $location_id): void
    {
        $this->session->set('transfer_destination_location', $location_id);
    }

    /**
     * Get reference number
     *
     * @return string
     */
    public function get_reference(): string
    {
        return $this->session->get('transfer_reference') ?? '';
    }

    /**
     * Set reference number
     *
     * @param string $reference
     * @return void
     */
    public function set_reference(string $reference): void
    {
        $this->session->set('transfer_reference', $reference);
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function get_comment(): string
    {
        $comment = $this->session->get('transfer_comment');

        return empty($comment) ? '' : $comment;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return void
     */
    public function set_comment(string $comment): void
    {
        $this->session->set('transfer_comment', $comment);
    }

    /**
     * Add item to transfer cart
     *
     * @param string $item_id_or_number
     * @param int $quantity
     * @param string|null $description
     * @param string|null $serialnumber
     * @return bool
     */
    public function add_item(
        string $item_id_or_number,
        int $quantity = 1,
        ?string $description = null,
        ?string $serialnumber = null
    ): bool {
        $item_info = $this->item->get_info_by_id_or_number($item_id_or_number, false);

        if (empty($item_info)) {
            return false;
        }

        $item_id = $item_info->item_id;
        $cart = $this->get_cart();

        // Check if item already in cart
        $max_key = 0;
        $item_already_in_cart = false;
        $update_key = 0;

        foreach ($cart as $item) {
            if ($max_key <= $item['line']) {
                $max_key = $item['line'];
            }

            if ($item['item_id'] == $item_id) {
                $item_already_in_cart = true;
                $update_key = $item['line'];
            }
        }

        $insert_key = $max_key + 1;

        if ($item_already_in_cart) {
            $cart[$update_key]['quantity'] += $quantity;
        } else {
            // Get item quantity in source location
            $source_location = $this->get_source_location();
            $quantity_model = model(\App\Models\Item_quantity::class);
            $quantity_info = $quantity_model->get_item_quantity($item_id, $source_location);
            $in_stock = $quantity_info->quantity;

            $cart[$insert_key] = [
                'line'          => $insert_key,
                'item_id'       => $item_id,
                'item_number'   => $item_info->item_number,
                'name'          => $item_info->name,
                'quantity'      => $quantity,
                'description'   => $description ?? '',
                'serialnumber'  => $serialnumber ?? '',
                'in_stock'      => $in_stock,
                'location_id'   => $source_location
            ];
        }

        $this->set_cart($cart);
        return true;
    }

    /**
     * Delete item from transfer cart
     *
     * @param int $line
     * @return void
     */
    public function delete_item(int $line): void
    {
        $cart = $this->get_cart();
        unset($cart[$line]);
        $this->set_cart($cart);
    }

    /**
     * Edit item in transfer cart
     *
     * @param int $line
     * @param int $quantity
     * @param string|null $description
     * @param string|null $serialnumber
     * @return void
     */
    public function edit_item(
        int $line,
        int $quantity,
        ?string $description = null,
        ?string $serialnumber = null
    ): void {
        $cart = $this->get_cart();

        if (isset($cart[$line])) {
            $cart[$line]['quantity'] = $quantity;

            if ($description !== null) {
                $cart[$line]['description'] = $description;
            }

            if ($serialnumber !== null) {
                $cart[$line]['serialnumber'] = $serialnumber;
            }

            $this->set_cart($cart);
        }
    }

    /**
     * Get total items in cart
     *
     * @return int
     */
    public function get_total_items(): int
    {
        $cart = $this->get_cart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['quantity'];
        }

        return $total;
    }

    /**
     * Clear all transfer session data
     *
     * @return void
     */
    public function clear_all(): void
    {
        $this->session->remove('transfer_cart');
        $this->session->remove('transfer_source_location');
        $this->session->remove('transfer_destination_location');
        $this->session->remove('transfer_reference');
        $this->session->remove('transfer_comment');
    }
}
