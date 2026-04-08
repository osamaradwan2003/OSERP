<?php


/**
 * @param $cart
 * @return array
 *
 * print kit invoice and hidden all items in it and if is two kit incres

 */
function print_kit_customer_invoice($cart): array
{
    $newCart = ['kit' => [], 'items' => []];

    // Group kit items by kit_group_name
    $kitGroups = [];
    $nonKitItems = [];

    foreach ($cart as $key => $value) {
        if ($value['is_kit_item'] && !empty($value['kit_group_name'])) {
            $kitGroups[$value['kit_group_name']][] = $value;
        } else {
            $nonKitItems[] = $value;
        }
    }

    // Helper function to calculate unit price with discount
    $getDiscountedUnitPrice = function($item) {
        if ($item['discount'] > 0) {
            if ($item['discount_type'] == 0) {
                // Percentage discount
                return $item['price'] * (1 - ($item['discount'] / 100));
            } else {
                // Fixed amount discount
                return $item['price'] - $item['discount'];
            }
        }
        return $item['price'];
    };

    // Process kit groups
    foreach ($kitGroups as $kitGroupName => $items) {
        if (count($items) > 1) {
            // Multiple items in kit - find max possible kits
            $maxKits = PHP_INT_MAX;

            // Calculate max kits based on each item
            foreach ($items as $item) {
                $qtInKit = $item['qt_in_kit'] ?? 1;
                $possibleKits = intval($item['quantity'] / $qtInKit);
                $maxKits = min($maxKits, $possibleKits);
            }

            // Calculate total discounted price for one complete kit
            $kitUnitPrice = 0;
            foreach ($items as $item) {
                $qtInKit = $item['qt_in_kit'] ?? 1;
                $discountedUnitPrice = $getDiscountedUnitPrice($item);
                $kitUnitPrice += $discountedUnitPrice * $qtInKit;
            }

            // Add kit to kits array with max possible quantity and calculated price
            if ($maxKits > 0) {
                $newCart['kit'][$kitGroupName] = array_merge($items[0], [
                    'name' => $kitGroupName,
                    'quantity' => $maxKits,
                    'items_in_kit' => count($items),
                    'price' => $kitUnitPrice * $maxKits,
                    'unit_price' => $kitUnitPrice,
                    'total' => $kitUnitPrice * $maxKits,
                    'discounted_total' => $kitUnitPrice * $maxKits,
                ]);
            }

            // Calculate remainders for each item
            foreach ($items as $item) {
                $qtInKit = $item['qt_in_kit'] ?? 1;
                $remainder = $item['quantity'] - ($maxKits * $qtInKit);
                if ($remainder > 0) {
                    $discountedUnitPrice = $getDiscountedUnitPrice($item);
                    $newCart['items'][] = array_merge($item, [
                        'quantity' => $remainder,
                        'total' => $discountedUnitPrice * $remainder,
                        'discounted_total' => $discountedUnitPrice * $remainder,
                    ]);
                }
            }
        } else {
            // Single item kit
            $item = $items[0];
            $qtInKit = $item['qt_in_kit'] ?? 1;
            $completeKits = intval($item['quantity'] / $qtInKit);
            $remainder = $item['quantity'] % $qtInKit;

            $discountedUnitPrice = $getDiscountedUnitPrice($item);

            if ($completeKits > 0) {
                $newCart['kit'][$kitGroupName] = array_merge($item, [
                    'name' => $kitGroupName,
                    'quantity' => $completeKits,
                    'price' => $discountedUnitPrice * $qtInKit * $completeKits,
                    'unit_price' => $discountedUnitPrice * $qtInKit,
                    'total' => $discountedUnitPrice * $qtInKit * $completeKits,
                    'discounted_total' => $discountedUnitPrice * $qtInKit * $completeKits,
                ]);
            }

            if ($remainder > 0) {
                $newCart['items'][] = array_merge($item, [
                    'quantity' => $remainder,
                    'total' => $discountedUnitPrice * $remainder,
                    'discounted_total' => $discountedUnitPrice * $remainder,
                ]);
            }
        }
    }

    // Add non-kit items
    foreach ($nonKitItems as $item) {
        $newCart['items'][] = $item;
    }

    $newCart = array_merge($newCart['kit'], $newCart['items']);

    return $newCart;
}


function print_array($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

$cart = [
    1 => [
        'item_id' => 1,
        'item_location' => 1,
        'stock_name' => 'stock',
        'line' => 1,
        'name' => 'زيت اسكلابشر',
        'item_number' => '',
        'attribute_values' => '',
        'attribute_dtvalues' => '',
        'description' => '',
        'serialnumber' => '',
        'allow_alt_description' => 0,
        'is_serialized' => 0,
        'quantity' => 12.0000,
        'discount' => 0,
        'discount_type' => 0,
        'in_stock' => 550.000,
        'price' => 10.00,
        'cost_price' => 6.00,
        'total' => 120.0000,
        'discounted_total' => 120.0000,
        'print_option' => 0,
        'stock_type' => 0,
        'item_type' => 0,
        'hsn_code' => '',
        'tax_category_id' => '',
        'is_kit_item' => 1,
        'kit_group_name' => 'زجاجة اسكلابشر 50مم'
    ],

    2 => [
        'item_id' => 2,
        'item_location' => 1,
        'stock_name' => 'stock',
        'line' => 2,
        'name' => 'زجاجة 50مم',
        'item_number' => '',
        'attribute_values' => '',
        'attribute_dtvalues' => '',
        'description' => '',
        'serialnumber' => '',
        'allow_alt_description' => 0,
        'is_serialized' => 0,
        'quantity' => 3,
        'discount' => 0,
        'discount_type' => 0,
        'in_stock' => 19.000,
        'price' => 55,
        'cost_price' => 50.00,
        'total' => 165.0000,
        'discounted_total' => 165.0000,
        'print_option' => 0,
        'stock_type' => 0,
        'item_type' => 0,
        'hsn_code' => '',
        'tax_category_id' => '',
        'is_kit_item' => 1,
        'kit_group_name' => 'زجاجة اسكلابشر 50مم'
    ],

    3 => [
        'item_id' => 5,
        'item_location' => 1,
        'stock_name' => 'stock',
        'line' => 3,
        'name' => 'عود',
        'item_number' => '',
        'attribute_values' => '',
        'attribute_dtvalues' => '',
        'description' => '',
        'serialnumber' => '',
        'allow_alt_description' => 0,
        'is_serialized' => 0,
        'quantity' => 1,
        'discount' => 0,
        'discount_type' => 0,
        'in_stock' => 49.000,
        'price' => 10.00,
        'cost_price' => 5.00,
        'total' => 10.0000,
        'discounted_total' => 10.0000,
        'print_option' => 0,
        'stock_type' => 0,
        'item_type' => 0,
        'hsn_code' => '',
        'tax_category_id' => '',
        'is_kit_item' => '',
        'kit_group_name' => ''
    ]
];
