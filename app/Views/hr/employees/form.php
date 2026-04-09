<?php
/**
 * @var object|null $person_info
 * @var array $department_options
 * @var array $position_options
 * @var array $shift_options
 * @var array $all_modules
 * @var array $all_subpermissions
 * @var int $employee_id
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/employee/save' . ($employee_id ? "/$employee_id" : ''), ['id' => 'employee_form', 'class' => 'form-horizontal']) ?>

<ul class="nav nav-tabs" data-tabs="tabs">
    <li class="active"><a data-toggle="tab" href="#personal_info"><?= lang('Hr.personal_info') ?></a></li>
    <li><a data-toggle="tab" href="#login_info"><?= lang('Hr.login_info') ?></a></li>
    <li><a data-toggle="tab" href="#hr_profile"><?= lang('Hr.hr_profile') ?></a></li>
    <li><a data-toggle="tab" href="#attachments"><?= lang('Hr.documents') ?></a></li>
</ul>

<div class="tab-content" style="margin-top: 15px;">
    <div class="tab-pane fade in active" id="personal_info">
        <div class="form-group">
            <?= form_label(lang('Hr.first_name'), 'first_name', ['class' => 'required control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'first_name',
                    'id' => 'first_name',
                    'class' => 'form-control',
                    'value' => $person_info->first_name ?? '',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.last_name'), 'last_name', ['class' => 'required control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'last_name',
                    'id' => 'last_name',
                    'class' => 'form-control',
                    'value' => $person_info->last_name ?? '',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.email'), 'email', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'email',
                    'id' => 'email',
                    'class' => 'form-control',
                    'type' => 'email',
                    'value' => $person_info->email ?? '',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.phone'), 'phone_number', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'phone_number',
                    'id' => 'phone_number',
                    'class' => 'form-control',
                    'value' => $person_info->phone_number ?? '',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.address'), 'address', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_textarea([
                    'name' => 'address',
                    'id' => 'address',
                    'class' => 'form-control',
                    'rows' => 3,
                ], $person_info->address ?? '') ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.country'), 'country', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?php
                $countries = [
                    '' => lang('Hr.select_country'),
                    'United States' => 'United States',
                    'United Kingdom' => 'United Kingdom',
                    'Canada' => 'Canada',
                    'Australia' => 'Australia',
                    'Germany' => 'Germany',
                    'France' => 'France',
                    'Spain' => 'Spain',
                    'Italy' => 'Italy',
                    'Netherlands' => 'Netherlands',
                    'Belgium' => 'Belgium',
                    'Switzerland' => 'Switzerland',
                    'Austria' => 'Austria',
                    'Poland' => 'Poland',
                    'Sweden' => 'Sweden',
                    'Norway' => 'Norway',
                    'Denmark' => 'Denmark',
                    'Finland' => 'Finland',
                    'Portugal' => 'Portugal',
                    'Greece' => 'Greece',
                    'Turkey' => 'Turkey',
                    'Russia' => 'Russia',
                    'China' => 'China',
                    'Japan' => 'Japan',
                    'South Korea' => 'South Korea',
                    'India' => 'India',
                    'Brazil' => 'Brazil',
                    'Mexico' => 'Mexico',
                    'Argentina' => 'Argentina',
                    'South Africa' => 'South Africa',
                    'Egypt' => 'Egypt',
                    'UAE' => 'United Arab Emirates',
                    'Saudi Arabia' => 'Saudi Arabia',
                    'Singapore' => 'Singapore',
                    'Malaysia' => 'Malaysia',
                    'Indonesia' => 'Indonesia',
                    'Thailand' => 'Thailand',
                    'Philippines' => 'Philippines',
                    'Vietnam' => 'Vietnam',
                    'New Zealand' => 'New Zealand',
                    'Ireland' => 'Ireland',
                ];
                echo form_dropdown('country', $countries, $person_info->country ?? '', ['class' => 'form-control', 'id' => 'country']);
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.city'), 'city', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?php
                $cities_by_country = [
                    'United States' => [
                        'New York' => 'New York',
                        'Los Angeles' => 'Los Angeles',
                        'Chicago' => 'Chicago',
                        'Houston' => 'Houston',
                        'Phoenix' => 'Phoenix',
                        'Philadelphia' => 'Philadelphia',
                        'San Antonio' => 'San Antonio',
                        'San Diego' => 'San Diego',
                        'Dallas' => 'Dallas',
                        'San Jose' => 'San Jose',
                        'Austin' => 'Austin',
                        'Jacksonville' => 'Jacksonville',
                        'San Francisco' => 'San Francisco',
                        'Seattle' => 'Seattle',
                        'Denver' => 'Denver',
                        'Boston' => 'Boston',
                        'Miami' => 'Miami',
                        'Atlanta' => 'Atlanta',
                    ],
                    'United Kingdom' => [
                        'London' => 'London',
                        'Birmingham' => 'Birmingham',
                        'Manchester' => 'Manchester',
                        'Glasgow' => 'Glasgow',
                        'Liverpool' => 'Liverpool',
                        'Edinburgh' => 'Edinburgh',
                        'Bristol' => 'Bristol',
                    ],
                    'Canada' => [
                        'Toronto' => 'Toronto',
                        'Montreal' => 'Montreal',
                        'Vancouver' => 'Vancouver',
                        'Calgary' => 'Calgary',
                        'Edmonton' => 'Edmonton',
                        'Ottawa' => 'Ottawa',
                        'Winnipeg' => 'Winnipeg',
                        'Quebec City' => 'Quebec City',
                    ],
                    'Australia' => [
                        'Sydney' => 'Sydney',
                        'Melbourne' => 'Melbourne',
                        'Brisbane' => 'Brisbane',
                        'Perth' => 'Perth',
                        'Adelaide' => 'Adelaide',
                        'Gold Coast' => 'Gold Coast',
                        'Canberra' => 'Canberra',
                    ],
                    'Germany' => [
                        'Berlin' => 'Berlin',
                        'Hamburg' => 'Hamburg',
                        'Munich' => 'Munich',
                        'Cologne' => 'Cologne',
                        'Frankfurt' => 'Frankfurt',
                        'Stuttgart' => 'Stuttgart',
                        'Düsseldorf' => 'Düsseldorf',
                        'Dortmund' => 'Dortmund',
                    ],
                    'France' => [
                        'Paris' => 'Paris',
                        'Marseille' => 'Marseille',
                        'Lyon' => 'Lyon',
                        'Toulouse' => 'Toulouse',
                        'Nice' => 'Nice',
                        'Nantes' => 'Nantes',
                        'Strasbourg' => 'Strasbourg',
                        'Bordeaux' => 'Bordeaux',
                    ],
                    'UAE' => [
                        'Dubai' => 'Dubai',
                        'Abu Dhabi' => 'Abu Dhabi',
                        'Sharjah' => 'Sharjah',
                        'Al Ain' => 'Al Ain',
                        'Ajman' => 'Ajman',
                    ],
                    'Saudi Arabia' => [
                        'Riyadh' => 'Riyadh',
                        'Jeddah' => 'Jeddah',
                        'Mecca' => 'Mecca',
                        'Medina' => 'Medina',
                        'Dammam' => 'Dammam',
                    ],
                ];
                echo form_dropdown('city', ['' => lang('Hr.select_city')] + ($cities_by_country[$person_info->country ?? ''] ?? []), $person_info->city ?? '', ['class' => 'form-control', 'id' => 'city', 'data-country' => $person_info->country ?? '']);
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.state'), 'state', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'state',
                    'id' => 'state',
                    'class' => 'form-control',
                    'value' => $person_info->state ?? '',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.zip'), 'zip', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'zip',
                    'id' => 'zip',
                    'class' => 'form-control',
                    'value' => $person_info->zip ?? '',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.comments'), 'comments', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_textarea([
                    'name' => 'comments',
                    'id' => 'comments',
                    'class' => 'form-control',
                    'rows' => 3,
                ], $person_info->comments ?? '') ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="login_info">
        <?php 
        $has_existing_login = !empty($person_info->username);
        $password_required = $employee_id == 0; // Only required for new employees
        ?>
        
        <div class="form-group">
            <div class="col-xs-9 col-xs-offset-3">
                <div class="checkbox">
                    <label>
                        <?= form_checkbox('has_login_account', 1, $has_existing_login, ['id' => 'has_login_account']) ?>
                        <strong><?= lang('Hr.has_login_account') ?></strong>
                    </label>
                </div>
                <p class="help-block"><?= lang('Hr.login_account_info') ?></p>
            </div>
        </div>

        <div id="login_fields" style="<?= $has_existing_login ? '' : 'display: none;' ?>">
            <div class="form-group">
                <?= form_label(lang('Hr.username'), 'username', ['class' => 'required control-label col-xs-3']) ?>
                <div class="col-xs-9">
                    <?= form_input([
                        'name' => 'username',
                        'id' => 'username',
                        'class' => 'form-control',
                        'value' => $person_info->username ?? '',
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.password'), 'password', ['class' => ($password_required ? 'required ' : '') . 'control-label col-xs-3']) ?>
                <div class="col-xs-9">
                    <?= form_password([
                        'name' => 'password',
                        'id' => 'password',
                        'class' => 'form-control',
                    ]) ?>
                    <?php if (!$password_required): ?>
                    <p class="help-block"><?= lang('Hr.leave_empty_keep_current') ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <?= form_label(lang('Hr.repeat_password'), 'repeat_password', ['class' => ($password_required ? 'required ' : '') . 'control-label col-xs-3']) ?>
                <div class="col-xs-9">
                    <?= form_password([
                        'name' => 'repeat_password',
                        'id' => 'repeat_password',
                        'class' => 'form-control',
                    ]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= form_label(lang('Employees.language'), 'language', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-9">
                    <?php
                    $languages = get_languages();
                    $languages[':'] = lang('Employees.system_language');
                    $language_code = current_language_code();
                    $language = current_language();
                    if ($language_code === current_language_code(true)) {
                        $language_code = '';
                        $language = '';
                    }
                    echo form_dropdown('language', $languages, $person_info->language ?? $language_code, ['class' => 'form-control', 'id' => 'language']);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="hr_profile">
        <div class="form-group">
            <?= form_label(lang('Hr.employee_number'), 'employee_number', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'employee_number',
                    'id' => 'employee_number',
                    'class' => 'form-control',
                    'value' => $profile['employee_number'] ?? '',
                    'placeholder' => 'e.g., EMP-001'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.department'), 'department_id', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_dropdown('department_id', ['' => lang('Common.none_selected_text')] + $department_options, (string)($profile['department_id'] ?? ''), ['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.position'), 'position_id', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_dropdown('position_id', ['' => lang('Common.none_selected_text')] + $position_options, (string)($profile['position_id'] ?? ''), ['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.shift'), 'shift_id', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_dropdown('shift_id', ['' => lang('Common.none_selected_text')] + $shift_options, (string)($profile['shift_id'] ?? ''), ['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.hire_date'), 'hire_date', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'hire_date',
                    'id' => 'hire_date',
                    'class' => 'form-control',
                    'type' => 'date',
                    'value' => $profile['hire_date'] ?? date('Y-m-d')
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.employment_type'), 'employment_type', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_dropdown('employment_type', [
                    'full_time' => lang('Hr.full_time'),
                    'part_time' => lang('Hr.part_time'),
                    'contract' => lang('Hr.contract'),
                    'intern' => lang('Hr.intern')
                ], (string)($profile['employment_type'] ?? 'full_time'), ['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.basic_salary'), 'basic_salary', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'basic_salary',
                    'id' => 'basic_salary',
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '0.01',
                    'value' => (string)($profile['basic_salary'] ?? '0')
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.hourly_rate'), 'hourly_rate', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'hourly_rate',
                    'id' => 'hourly_rate',
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '0.01',
                    'value' => (string)($profile['hourly_rate'] ?? '0')
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.bank_name'), 'bank_name', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'bank_name',
                    'id' => 'bank_name',
                    'class' => 'form-control',
                    'value' => $profile['bank_name'] ?? ''
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.bank_account'), 'bank_account', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'bank_account',
                    'id' => 'bank_account',
                    'class' => 'form-control',
                    'value' => $profile['bank_account'] ?? ''
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.tax_id'), 'tax_id', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'tax_id',
                    'id' => 'tax_id',
                    'class' => 'form-control',
                    'value' => $profile['tax_id'] ?? ''
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= form_label(lang('Hr.social_security_number'), 'social_security_number', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-9">
                <?= form_input([
                    'name' => 'social_security_number',
                    'id' => 'social_security_number',
                    'class' => 'form-control',
                    'value' => $profile['social_security_number'] ?? ''
                ]) ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="attachments">
        <div id="attachments_container">
            <div class="text-right" style="margin-bottom: 15px;">
                <button type="button" class="btn btn-success" id="add_attachment_btn">
                    <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.add_document') ?>
                </button>
            </div>
            
            <div id="attachment_upload_form" style="display: none; margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-radius: 4px;">
                <h4><?= lang('Hr.upload_document') ?></h4>
                <div class="form-group">
                    <?= form_label(lang('Hr.document_type'), 'doc_type', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-9">
                        <?= form_dropdown('doc_type', [
                            'id' => lang('Hr.doc_type_id'),
                            'passport' => lang('Hr.doc_type_passport'),
                            'resume' => lang('Hr.doc_type_resume'),
                            'contract' => lang('Hr.doc_type_contract'),
                            'certificate' => lang('Hr.doc_type_certificate'),
                            'license' => lang('Hr.doc_type_license'),
                            'other' => lang('Hr.doc_type_other'),
                        ], '', ['class' => 'form-control', 'id' => 'upload_doc_type']) ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= form_label(lang('Hr.document_title'), 'attachment_title', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-9">
                        <?= form_input([
                            'name' => 'attachment_title',
                            'id' => 'upload_attachment_title',
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= form_label(lang('Hr.expiry_date'), 'expiry_date', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-9">
                        <?= form_input([
                            'name' => 'expiry_date',
                            'id' => 'upload_expiry_date',
                            'class' => 'form-control',
                            'type' => 'date',
                        ]) ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= form_label(lang('Hr.file'), 'attachment_file', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-9">
                        <input type="file" name="attachment_file" id="upload_attachment_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <p class="help-block"><?= lang('Hr.drag_drop_upload') ?><br><?= lang('Hr.max_file_size') ?>: 5MB<br><?= lang('Hr.allowed_file_types') ?></p>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-9 col-xs-offset-3">
                        <button type="button" class="btn btn-primary" id="upload_attachment_btn"><?= lang('Hr.upload_document') ?></button>
                        <button type="button" class="btn btn-default" id="cancel_upload_btn"><?= lang('Common.cancel') ?></button>
                    </div>
                </div>
            </div>
            
            <div id="attachments_list">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th><?= lang('Hr.document_type') ?></th>
                            <th><?= lang('Hr.document_title') ?></th>
                            <th><?= lang('Hr.file_name') ?></th>
                            <th><?= lang('Hr.file_size') ?></th>
                            <th><?= lang('Hr.expiry_date') ?></th>
                            <th><?= lang('Hr.verified') ?></th>
                            <th><?= lang('Common.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody id="attachments_tbody">
                        <?php if (!empty($attachments)): ?>
                            <?php foreach ($attachments as $attachment): ?>
                                <tr data-id="<?= $attachment['id'] ?>">
                                    <td><?= lang('Hr.doc_type_' . $attachment['doc_type']) ?></td>
                                    <td><?= esc($attachment['title']) ?></td>
                                    <td><?= esc($attachment['file_name']) ?></td>
                                    <td><?= number_format($attachment['file_size'] / 1024, 2) ?> KB</td>
                                    <td><?= $attachment['expiry_date'] ? date('Y-m-d', strtotime($attachment['expiry_date'])) : '-' ?></td>
                                    <td>
                                        <?php if ($attachment['is_verified']): ?>
                                            <span class="label label-success"><?= lang('Hr.verified') ?></span>
                                        <?php else: ?>
                                            <span class="label label-default"><?= lang('Hr.not_verified') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('hr/attachment/download/' . $attachment['id']) ?>" class="btn btn-xs btn-info" title="<?= lang('Common.download') ?>">
                                            <span class="glyphicon glyphicon-download-alt"></span>
                                        </a>
                                        <button type="button" class="btn btn-xs btn-danger delete-attachment" data-id="<?= $attachment['id'] ?>" title="<?= lang('Common.delete') ?>">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="no_attachments_row">
                                <td colspan="7" class="text-center text-muted"><?= lang('Hr.no_attachments') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= form_hidden('person_id', (string)($person_info->person_id ?? '')) ?>
<?= form_hidden('employee_id', (string)($employee_id ?: 0)) ?>

<script type="text/javascript">
$(document).ready(function() {
    // City options by country
    var citiesByCountry = {
        'United States': ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose', 'Austin', 'Jacksonville', 'San Francisco', 'Seattle', 'Denver', 'Boston', 'Miami', 'Atlanta'],
        'United Kingdom': ['London', 'Birmingham', 'Manchester', 'Glasgow', 'Liverpool', 'Edinburgh', 'Bristol'],
        'Canada': ['Toronto', 'Montreal', 'Vancouver', 'Calgary', 'Edmonton', 'Ottawa', 'Winnipeg', 'Quebec City'],
        'Australia': ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide', 'Gold Coast', 'Canberra'],
        'Germany': ['Berlin', 'Hamburg', 'Munich', 'Cologne', 'Frankfurt', 'Stuttgart', 'Düsseldorf', 'Dortmund'],
        'France': ['Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Bordeaux'],
        'UAE': ['Dubai', 'Abu Dhabi', 'Sharjah', 'Al Ain', 'Ajman'],
        'Saudi Arabia': ['Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam']
    };

    // Update city dropdown when country changes
    $('#country').change(function() {
        var country = $(this).val();
        var $citySelect = $('#city');
        var currentCity = $citySelect.data('country') || '';
        
        $citySelect.empty();
        $citySelect.append('<option value=""><?= lang('Hr.select_city') ?></option>');
        
        if (country && citiesByCountry[country]) {
            $.each(citiesByCountry[country], function(index, city) {
                var selected = (city === currentCity) ? 'selected' : '';
                $citySelect.append('<option value="' + city + '" ' + selected + '>' + city + '</option>');
            });
        }
    });

    // Toggle login fields visibility
    $('#has_login_account').change(function() {
        $('#login_fields').toggle(this.checked);
        
        // Update required attribute on username
        if (this.checked) {
            $('#username').attr('required', 'required');
        } else {
            $('#username').removeAttr('required');
            $('#username, #password, #repeat_password').val('');
        }
        
        // Re-validate form
        if (typeof $('#employee_form').validate === 'function') {
            $('#employee_form').valid();
        }
    });

    var rules = {
        first_name: 'required',
        last_name: 'required',
        username: {
            required: function() {
                return $('#has_login_account').is(':checked');
            }
        },
        password: {
            minlength: 4
        },
        repeat_password: {
            equalTo: '#password'
        }
    };
    
    <?php if ($password_required): ?>
    rules.password.required = true;
    rules.repeat_password.required = true;
    <?php endif; ?>
    
    $('#add_attachment_btn').click(function() {
        $('#attachment_upload_form').slideDown();
        $('#add_attachment_btn').hide();
    });
    
    $('#cancel_upload_btn').click(function() {
        $('#attachment_upload_form').slideUp();
        $('#add_attachment_btn').show();
        $('#upload_doc_type').val('id');
        $('#upload_attachment_title').val('');
        $('#upload_expiry_date').val('');
        $('#upload_attachment_file').val('');
    });
    
    $('#upload_attachment_btn').click(function() {
        var employeeId = <?= (int)($employee_id ?: 0) ?>;
        var formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('employee_id', employeeId);
        formData.append('doc_type', $('#upload_doc_type').val());
        formData.append('title', $('#upload_attachment_title').val());
        formData.append('expiry_date', $('#upload_expiry_date').val());
        
        var fileInput = document.getElementById('upload_attachment_file');
        if (fileInput.files.length === 0) {
            alert('<?= lang('Hr.please_select_file') ?>');
            return;
        }
        formData.append('attachment_file', fileInput.files[0]);
        
        $.ajax({
            url: '<?= site_url('hr/attachment/upload') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#no_attachments_row').remove();
                    var docTypeLabel = $('#upload_doc_type option:selected').text();
                    var expiryDate = $('#upload_expiry_date').val() || '-';
                    var fileSize = (response.file_size / 1024).toFixed(2) + ' KB';
                    
                    var newRow = '<tr data-id="' + response.id + '">' +
                        '<td>' + docTypeLabel + '</td>' +
                        '<td>' + escHtml($('#upload_attachment_title').val()) + '</td>' +
                        '<td>' + escHtml(response.file_name) + '</td>' +
                        '<td>' + fileSize + '</td>' +
                        '<td>' + expiryDate + '</td>' +
                        '<td><span class="label label-default"><?= lang('Hr.not_verified') ?></span></td>' +
                        '<td>' +
                        '<a href="<?= site_url('hr/attachment/download/') ?>' + response.id + '" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-download-alt"></span></a> ' +
                        '<button type="button" class="btn btn-xs btn-danger delete-attachment" data-id="' + response.id + '"><span class="glyphicon glyphicon-trash"></span></button>' +
                        '</td></tr>';
                    $('#attachments_tbody').append(newRow);
                    
                    $('#cancel_upload_btn').click();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('<?= lang('Common.error') ?>');
            }
        });
    });
    
    $(document).on('click', '.delete-attachment', function() {
        if (!confirm('<?= lang('Hr.confirm_delete_attachment') ?>')) {
            return;
        }
        
        var attachmentId = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: '<?= site_url('hr/attachment/delete') ?>',
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>', id: attachmentId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $row.remove();
                    if ($('#attachments_tbody tr').length === 0) {
                        $('#attachments_tbody').html('<tr id="no_attachments_row"><td colspan="7" class="text-center text-muted"><?= lang('Hr.no_attachments') ?></td></tr>');
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('<?= lang('Common.error') ?>');
            }
        });
    });
    
    function escHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    $('#employee_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                dataType: 'json'
            });
        },
        rules: rules,
        messages: {
            username: {
                required: '<?= lang('Hr.username_required_when_login') ?>'
            },
            repeat_password: {
                equalTo: '<?= lang('Employees.password_mismatch') ?>'
            }
        }
    });
});
</script>
