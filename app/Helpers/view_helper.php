<?php

/**
 * View Helper Functions
 *
 * Provides helper functions for rendering view components
 * throughout the OSPOS application.
 */

if (!function_exists('render_form_input')) {
    /**
     * Render a form text input component
     *
     * @param string $name Field name
     * @param string $label Label translation key
     * @param mixed $value Field value
     * @param array $options Additional options
     * @return string
     */
    function render_form_input(string $name, string $label, $value = '', array $options = []): string
    {
        return view('Components/Forms/form_text_input', [
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_form_dropdown')) {
    /**
     * Render a form dropdown component
     *
     * @param string $name Field name
     * @param string $label Label translation key
     * @param array $select_options Select options
     * @param mixed $selected Selected value
     * @param array $attrs Additional attributes
     * @return string
     */
    function render_form_dropdown(string $name, string $label, array $select_options, $selected = null, array $attrs = []): string
    {
        return view('Components/Forms/form_dropdown', [
            'name' => $name,
            'label' => $label,
            'options' => $select_options,
            'selected' => $selected,
            'attrs' => $attrs,
        ]);
    }
}

if (!function_exists('render_form_checkbox')) {
    /**
     * Render a form checkbox component
     *
     * @param string $name Field name
     * @param string $label Label translation key
     * @param bool $checked Whether checkbox is checked
     * @param array $options Additional options
     * @return string
     */
    function render_form_checkbox(string $name, string $label, bool $checked = false, array $options = []): string
    {
        return view('Components/Forms/form_checkbox', [
            'name' => $name,
            'label' => $label,
            'checked' => $checked,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_form_radio_group')) {
    /**
     * Render a form radio group component
     *
     * @param string $name Field name
     * @param string $label Label translation key
     * @param array $radio_options Radio options
     * @param mixed $selected Selected value
     * @param array $attrs Additional attributes
     * @return string
     */
    function render_form_radio_group(string $name, string $label, array $radio_options, $selected = null, array $attrs = []): string
    {
        return view('Components/Forms/form_radio_group', [
            'name' => $name,
            'label' => $label,
            'options' => $radio_options,
            'selected' => $selected,
            'attrs' => $attrs,
        ]);
    }
}

if (!function_exists('render_form_textarea')) {
    /**
     * Render a form textarea component
     *
     * @param string $name Field name
     * @param string $label Label translation key
     * @param string $value Field value
     * @param array $options Additional options
     * @return string
     */
    function render_form_textarea(string $name, string $label, string $value = '', array $options = []): string
    {
        return view('Components/Forms/form_textarea', [
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_form_datepicker')) {
    /**
     * Render a form datepicker component
     *
     * @param string $name Field name
     * @param string $label Label translation key
     * @param mixed $value Field value
     * @param array $options Additional options
     * @return string
     */
    function render_form_datepicker(string $name, string $label, $value = '', array $options = []): string
    {
        return view('Components/Forms/form_datepicker', [
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_form_file_upload')) {
    /**
     * Render a form file upload component
     *
     * @param string $name Field name
     * @param string $label Label translation key
     * @param array $options Additional options
     * @return string
     */
    function render_form_file_upload(string $name, string $label, array $options = []): string
    {
        return view('Components/Forms/form_file_upload', [
            'name' => $name,
            'label' => $label,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_form_section')) {
    /**
     * Render a form section component
     *
     * @param string $title Section title translation key
     * @param string $content Section content
     * @param array $options Additional options
     * @return string
     */
    function render_form_section(string $title, string $content = '', array $options = []): string
    {
        return view('Components/Forms/form_section', [
            'title' => $title,
            'content' => $content,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_form_actions')) {
    /**
     * Render form action buttons
     *
     * @param array $options Additional options
     * @return string
     */
    function render_form_actions(array $options = []): string
    {
        return view('Components/Forms/form_actions', [
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_title_bar')) {
    /**
     * Render a title bar with action buttons
     *
     * @param array $actions Action buttons configuration
     * @param string $class Additional CSS classes
     * @return string
     */
    function render_title_bar(array $actions, string $class = ''): string
    {
        return view('Components/Layout/title_bar', [
            'actions' => $actions,
            'class' => $class,
        ]);
    }
}

if (!function_exists('render_page_header')) {
    /**
     * Render a page header
     *
     * @param string $title Header title translation key
     * @param string $subtitle Optional subtitle translation key
     * @param array $options Additional options
     * @return string
     */
    function render_page_header(string $title, ?string $subtitle = null, array $options = []): string
    {
        return view('Components/Layout/page_header', [
            'title' => $title,
            'subtitle' => $subtitle,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_action_button')) {
    /**
     * Render an action button
     *
     * @param string $action Action type
     * @param string $label Button label translation key
     * @param array $options Additional options
     * @return string
     */
    function render_action_button(string $action, string $label = '', array $options = []): string
    {
        return view('Components/Buttons/action_button', [
            'action' => $action,
            'label' => $label,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_delete_button')) {
    /**
     * Render a delete button
     *
     * @param string $label Button label translation key
     * @param array $options Additional options
     * @return string
     */
    function render_delete_button(string $label = 'Common.delete', array $options = []): string
    {
        return view('Components/Buttons/delete_button', [
            'label' => $label,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_submit_button')) {
    /**
     * Render a submit button
     *
     * @param string $label Button label translation key
     * @param array $options Additional options
     * @return string
     */
    function render_submit_button(string $label = 'Common.submit', array $options = []): string
    {
        return view('Components/Buttons/submit_button', [
            'label' => $label,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_button_group')) {
    /**
     * Render a button group
     *
     * @param array $buttons Button configurations
     * @param array $options Additional options
     * @return string
     */
    function render_button_group(array $buttons, array $options = []): string
    {
        return view('Components/Buttons/button_group', [
            'buttons' => $buttons,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_alert_success')) {
    /**
     * Render a success alert
     *
     * @param string $message Alert message translation key
     * @param array $options Additional options
     * @return string
     */
    function render_alert_success(string $message, array $options = []): string
    {
        return view('Components/Feedback/alert_success', [
            'message' => $message,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_alert_error')) {
    /**
     * Render an error alert
     *
     * @param string $message Alert message translation key
     * @param array $options Additional options
     * @return string
     */
    function render_alert_error(string $message, array $options = []): string
    {
        return view('Components/Feedback/alert_error', [
            'message' => $message,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_alert_warning')) {
    /**
     * Render a warning alert
     *
     * @param string $message Alert message translation key
     * @param array $options Additional options
     * @return string
     */
    function render_alert_warning(string $message, array $options = []): string
    {
        return view('Components/Feedback/alert_warning', [
            'message' => $message,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_validation_errors')) {
    /**
     * Render validation errors
     *
     * @param array $errors Validation errors
     * @param array $options Additional options
     * @return string
     */
    function render_validation_errors(array $errors = [], array $options = []): string
    {
        return view('Components/Feedback/validation_errors', [
            'errors' => $errors,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_table_toolbar')) {
    /**
     * Render a table toolbar
     *
     * @param array $actions Action buttons
     * @param array $filters Filter configurations
     * @param array $options Additional options
     * @return string
     */
    function render_table_toolbar(array $actions = [], array $filters = [], array $options = []): string
    {
        return view('Components/Tables/table_toolbar', [
            'actions' => $actions,
            'filters' => $filters,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_table_actions')) {
    /**
     * Render table row actions
     *
     * @param array $actions Action configurations
     * @param mixed $row_id Row ID
     * @param array $options Additional options
     * @return string
     */
    function render_table_actions(array $actions, $row_id = null, array $options = []): string
    {
        return view('Components/Tables/table_actions', [
            'actions' => $actions,
            'row_id' => $row_id,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_confirm_dialog')) {
    /**
     * Render a confirmation dialog
     *
     * @param string $id Modal ID
     * @param string $title Dialog title translation key
     * @param string $message Confirmation message translation key
     * @param array $options Additional options
     * @return string
     */
    function render_confirm_dialog(string $id, string $title, string $message, array $options = []): string
    {
        return view('Components/Modals/confirm_dialog', [
            'id' => $id,
            'title' => $title,
            'message' => $message,
            'options' => $options,
        ]);
    }
}

if (!function_exists('render_form_modal')) {
    /**
     * Render a modal with form
     *
     * @param string $id Modal ID
     * @param string $title Modal title translation key
     * @param string $form_action Form action URL
     * @param string $form_content Form content
     * @param array $options Additional options
     * @return string
     */
    function render_form_modal(string $id, string $title, string $form_action, string $form_content = '', array $options = []): string
    {
        return view('Components/Modals/form_modal', [
            'id' => $id,
            'title' => $title,
            'form_action' => $form_action,
            'form_content' => $form_content,
            'options' => $options,
        ]);
    }
}
