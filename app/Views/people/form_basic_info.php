<?php
/**
 * Person Basic Information Form
 *
 * @var object $person_info
 * @var array $config
 * @var bool $basic_version Whether this is the basic version (affects gender field required status)
 */
?>

<?= render_form_input('first_name', 'Common.first_name', $person_info->first_name, [
	'required' => true,
]) ?>

<?= render_form_input('last_name', 'Common.last_name', $person_info->last_name, [
	'required' => true,
]) ?>

<?= render_form_radio_group('gender', 'Common.gender', [
	'1' => 'Common.gender_male',
	'0' => 'Common.gender_female',
], $person_info->gender, [
	'required' => !empty($basic_version),
	'input_class' => 'col-xs-4',
]) ?>

<?= render_form_input('email', 'Common.email', $person_info->email, [
	'type' => 'email',
	'icon' => 'envelope',
]) ?>

<?= render_form_input('phone_number', 'Common.phone_number', $person_info->phone_number, [
	'icon' => 'phone-alt',
]) ?>

<?= render_form_input('address_1', 'Common.address_1', $person_info->address_1) ?>

<?= render_form_input('address_2', 'Common.address_2', $person_info->address_2) ?>

<?= render_form_input('city', 'Common.city', $person_info->city) ?>

<?= render_form_input('state', 'Common.state', $person_info->state) ?>

<?= render_form_input('zip', 'Common.zip', $person_info->zip) ?>

<?= render_form_input('country', 'Common.country', $person_info->country) ?>

<?= render_form_textarea('comments', 'Common.comments', $person_info->comments ?? '') ?>

<script type="text/javascript">
// Validation and submit Handling
$(document).ready(function() {
	nominatim.init({
		fields: {
			postcode: {
				dependencies: ["postcode", "city", "state", "country"],
				response: {
					field: 'postalcode',
					format: ["postcode", "village|town|hamlet|city_district|city", "state", "country"]
				}
			},

			city: {
				dependencies: ["postcode", "city", "state", "country"],
				response: {
					format: ["postcode", "village|town|hamlet|city_district|city", "state", "country"]
				}
			},

			state: {
				dependencies: ["state", "country"]
			},

			country: {
				dependencies: ["state", "country"]
			}
		},
		language: '<?= current_language_code() ?>',
		country_codes: '<?= esc($config['country_codes'], 'js') ?>'
	});
});
</script>
