<?php
function getIndexElement($array, $search_value)
{
    foreach ($array as $key => $value) {
        if ($value['row_device_id'] == $search_value) {
            return $key;
            break;
        }
    }
}
?>

<style>
    .none {
        display: none;
    }
</style>

<ol class="breadcrumb">
    <li><a href="<?php echo action_management_controller_url(); ?>">Action management</a></li>
    <li class="active">Edit action</li>
</ol>

<h3>Device name: <?php echo $action['device_name']; ?></h3>

<form method="post" name="frmEditEvent">
    <input type="hidden" name="action_device_id" value="<?php echo $device['id']; ?>">
    <input type="hidden" name="action_type" value="<?php echo $action_type; ?>">

    <div class="btn-group">
        <label class="btn btn-primary">
            <input type="radio" name="action_status"
                   value="<?php echo ACTION_ENABLE; ?>" <?php if ($action['status'] == ACTION_ENABLE) echo 'checked'; ?>> Enable
        </label>
        <label class="btn btn-primary">
            <input type="radio" name="action_status"
                   value="<?php echo ACTION_DISABLE; ?>" <?php if ($action['status'] == ACTION_DISABLE) echo 'checked'; ?>> Disable
        </label>
    </div>

    <table border="0" style="width: 100%">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <p></p>
                <label class="control-label col-sm-2" for="amount">Setpoint</label>

                <div class="col-sm-2">
                    <input type="text" class="form-control" id="amount" disabled>
                    <input type="hidden" name="action_setpoint" id="action_setpoint">
                </div>
                <input id="range-slider" type="text"/>

                <p>&nbsp;</p>

                <h4>
                    Condition &nbsp;&nbsp;&nbsp;
                    <button type="button" id="btnAddNewCondition" class="btn btn-primary">Add new condition</button>
                </h4>

                <div id="InputsWrapper">
                    <?php
                        $count = 0;
                        foreach ($action_conditions as $conditions):
                            $remove_button_id = getIndexElement($input_devices_list, $conditions['row_device_id']);
                            if ($count == 0) {
                                $if_statement = 'If';
                                $label_size = 'col-sm-1';
                            } else {
                                $if_statement = 'Or if';
                                $label_size = 'col-sm-2';
                            }

                            if ($conditions['property_name'] == 'ON/OFF') {
                       ?>
                            <div class="col-sm-11" id="divCondition_<?php echo $remove_button_id; ?>"
                                 style="margin-bottom: 10px;">
                                <label
                                    class="control-label <?php echo $label_size; ?>"><?php echo $if_statement; ?></label>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control text-center"
                                           value="<?php echo $conditions['device_name']; ?>" disabled>
                                    <input type="hidden" value="<?php echo $conditions['row_device_id']; ?>"
                                           name="input_device[]">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control text-center" value="=" disabled>
                                    <input type="hidden" value="=" name="operator[]">
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control" name="condition_setpoint[]">
                                        <option value="1" <?php if($conditions['condition_setpoint'] == 1) echo 'selected'; ?>>ON</option>
                                        <option value="0" <?php if($conditions['condition_setpoint'] == 0) echo 'selected'; ?>>OFF</option>
                                    </select>
                                </div>
                                <button type="button" id="removeCondition"
                                        onclick="btnRemoveCondition(<?php echo $remove_button_id; ?>)"
                                        title="Remove">&times;</button>
                            </div>
                        <?php $count++; } else if ($conditions['property_name'] == 'Temperature sensor') { ?>
                            <div class="col-sm-12" id="divCondition_<?php echo $remove_button_id; ?>"
                                 style="margin-bottom: 10px;">
                                <label
                                    class="control-label <?php echo $label_size; ?>"><?php echo $if_statement; ?></label>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control text-center"
                                           value="<?php echo $conditions['device_name']; ?>" disabled>
                                    <input type="hidden" name="input_device[]"
                                           value="<?php echo $conditions['row_device_id']; ?>">
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control" name="operator[]">
                                        <option value="<"> &nbsp; <</option>
                                        <option value="<="> &nbsp; <=</option>
                                        <option value="="> &nbsp; =</option>
                                        <option value=">"> &nbsp; ></option>
                                        <option value=">="> &nbsp; >=</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="condition_setpoint[]"
                                           class="form-control"
                                           value="<?php echo $conditions['condition_setpoint']; ?>">
                                </div>
                                <button type="button" id="removeCondition"
                                        onclick="btnRemoveCondition(<?php echo $remove_button_id; ?>)"
                                        title="Remove">&times;</button>
                            </div>
                        <?php $count++; } endforeach; ?>
                </div>
            </td>
            <td style="vertical-align: top;">
                <h4>Exception &nbsp;
                    <label class="radio-inline">
                        <input type="radio" name="exception_type" id="radio-exception-day"
                               value="<?php echo EXCEPTION_TYPE_DAY; ?>"
                            <?php if ($action['exception_type'] == EXCEPTION_TYPE_DAY) echo 'checked'; ?>> Day
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="exception_type" id="radio-exception-duration"
                               value="<?php echo EXCEPTION_TYPE_DURATION; ?>"
                            <?php if ($action['exception_type'] == EXCEPTION_TYPE_DURATION) echo 'checked'; ?>> Duration
                    </label></h4>

                <div id="exception-day" class="none">
                    <div class="input-group date col-sm-3" id="datepicker_day">
                        <input class="form-control" type="text" name="exception_day"
                               value="<?php if ($action['exception_from'] and
                                   $action['exception_type'] == EXCEPTION_TYPE_DAY
                               ) echo $action['exception_from']; ?>"
                               readonly>
                                <span class="input-group-addon"><span
                                        class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>

                <table id="exception-duration" class="none" border="0" style="width: 100%">
                    <tr>
                        <td style="width: 8%">
                            <h4>From</h4>
                        </td>
                        <td style="width: 30%">
                            <div class="input-group date col-sm-11" id="datepicker_from">
                                <input class="form-control" type="text" name="exception_from"
                                       value="<?php if ($action['exception_from'] and
                                           $action['exception_type'] == EXCEPTION_TYPE_DURATION
                                       ) echo $action['exception_from']; ?>"
                                       readonly>
                                            <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </td>
                        <td style="width: 4%">
                            <h4>To</h4>
                        </td>
                        <td style="width: 50%">
                            <div class="input-group date col-sm-7" id="datepicker_to">
                                <input class="form-control" type="text" name="exception_to"
                                       value="<?php if ($action['exception_to'] and
                                           $action['exception_type'] == EXCEPTION_TYPE_DURATION
                                       ) echo $action['exception_to']; ?>"
                                       readonly>
                                            <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </td>
                    </tr>
                </table>

                <p>&nbsp;</p>

                <div id="divExceptionSetpoint" class="none">
                    <label class="control-label col-sm-2" for="amount2">Setpoint</label>

                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="amount2" disabled>
                        <input type="hidden" name="exception_setpoint" id="exception_setpoint">
                    </div>
                    <input style="width: 100%" id="range-slider2" type="text"/>
                </div>

                <p>&nbsp;</p><p>&nbsp;</p>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <?php if(!isset($_GET['callback'])) { ?>
                    <button type="button" class="btn btn-default"
                            onclick="window.location.href = '<?php echo action_management_controller_url(); ?>'">Cancel
                    </button>
                    <?php } else { if($_GET['callback'] == CALLBACK_ADD_EDIT_MODE_CONTROL): ?>
                    <button type="button" class="btn btn-default"
                            onclick="window.location.href = '<?php echo edit_mode_url($_GET['data']); ?>'">Cancel
                    </button>
                    <?php endif; } ?>
                </div>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
$(document).ready(function () {
    <?php if($action['action_setpoint']) { ?>
    $("#amount").val('<?php echo $action['action_setpoint'] , ' ' , $device['unit_name']; ?>');
    $("#action_setpoint").val('<?php echo $action['action_setpoint']; ?>');
    <?php } ?>
    $("#range-slider").slider({
        tooltip: 'hide',
        <?php if($device['min_value']): ?>min: <?php echo $device['min_value']; ?>, <?php endif; ?>
        <?php if($device['max_value']): ?>max: <?php echo $device['max_value']; ?>, <?php endif; ?>
        step: 1,
        <?php if($action['action_setpoint']) { ?>
        value: <?php echo $action['action_setpoint']; ?>
        <?php } ?>
    });
    $("#range-slider").on('slide', function (slideEvt) {
        $("#amount").val(slideEvt.value + ' <?php echo $device['unit_name']; ?>');
        $('#action_setpoint').val(slideEvt.value);
    });

    <?php if($action['exception_setpoint']) : ?>
    $("#amount2").val('<?php echo $action['exception_setpoint'] , ' ' , $device['unit_name']; ?>');
    $("#exception_setpoint").val('<?php echo $action['exception_setpoint']; ?>');
    <?php endif; ?>
    $("#range-slider2").slider({
        tooltip: 'hide',
        <?php if($device['min_value']): ?>min: <?php echo $device['min_value']; ?>, <?php endif; ?>
        <?php if($device['max_value']): ?>max: <?php echo $device['max_value']; ?>, <?php endif; ?>
        step: 1,
        <?php if($action['exception_setpoint']) : ?>
        value: <?php echo $action['exception_setpoint']; ?>
        <?php endif; ?>
    });
    $("#range-slider2").on('slide', function (slideEvt) {
        $("#amount2").val(slideEvt.value + ' <?php echo $device['unit_name']; ?>');
        $('#exception_setpoint').val(slideEvt.value);
    });

    $('#datepicker_day').datetimepicker({
        language: 'en',
        format: 'mm/dd/yyyy',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('#datepicker_from').datetimepicker({
        language: 'en',
        format: 'mm/dd/yyyy',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('#datepicker_to').datetimepicker({
        language: 'en',
        format: 'mm/dd/yyyy',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    // Show hide exception Day/Duration
    $("#radio-exception-day").on("change", function () {
        if ($(this).prop("checked")) {
            $('#exception-duration').addClass('none').siblings().removeClass('none');
            $('#divExceptionSetpoint').removeClass('none');
        }
    });
    $("#radio-exception-day").change();

    $("#radio-exception-duration").on("change", function () {
        if ($(this).prop("checked")) {
            $('#exception-day').addClass('none').siblings().removeClass('none');
            $('#divExceptionSetpoint').removeClass('none');
        }
    });
    $("#radio-exception-duration").change();
});

// Global variables
var MaxInputs = <?php echo count($input_devices_list); ?>; //maximum input boxes allowed
var InputsWrapper = $("#InputsWrapper"); //Input boxes wrapper ID
var ConditionHtml;
var count = <?php echo $count; ?>; //initial text box count

// Popover varialbes
var popover_options_list = [];
var clone_popover_options_list = [];

// Initial popover option list
<?php $i = 0; foreach ($new_input_devices as $input_device): ?>
popover_options_list[<?php echo $i; ?>] = {
    value: '<?php echo $input_device['device_name'] . ',' . $input_device['property_name'] . ',' . $input_device['row_device_id']; ?>',
    text: '<?php echo $input_device['device_name']; ?>'
};
<?php $i++; endforeach; ?>

<?php $i = 0; foreach ($input_devices_list as $input_device): ?>
clone_popover_options_list[<?php echo $i; ?>] = {
    value: '<?php echo $input_device['device_name'] . ',' . $input_device['property_name'] . ',' . $input_device['row_device_id']; ?>',
    text: '<?php echo $input_device['device_name']; ?>'
};
<?php $i++; endforeach; ?>

<?php if(count($action_conditions) == count($input_devices_list)) : ?>
    $("#btnAddNewCondition").prop('disabled', true);
<?php endif; ?>

function createNewPopover(arr, buttonID) {
    var options = '';

    // Initial options
    for (var i = 0; i < arr.length; i++) {
        options += '<option value="' + arr[i].value + '">' + arr[i].text + '</option>';
    }
    // Initial popover content
    var popover_content =
            '<div class="form-inline">' +
                '<select class="form-control" id="inputDevice" style="margin-right: 10px;">' +
                options +
                '</select>' +
                '<button class="btn btn-primary" type="button" id="btnContinue" onclick="btnContinueClick()">Continue</button>' +
                '</div>'
        ;

    $(buttonID).popover("destroy").popover({
        html: true,
        title: 'Input Device List <button type="button" class="close" id="' + buttonID + '">&times;</button>',
        content: popover_content,
        container: 'body'
    });
}

function getArrayIndexForKey(arr, key, val) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i][key] == val)
            return i;
    }
    return -1;
}

function btnContinueClick() {
    var SelectedInputDevice = $("#inputDevice").val()
    // Assign selected value to varialbe removeButtonID
    var removeButtonID = getArrayIndexForKey(clone_popover_options_list, 'value', SelectedInputDevice);
    var InputDevice = SelectedInputDevice.split(',');
    var ifStatement;
    var labelSize;

    if (count == 1) {
        ifStatement = 'If';
        labelSize = 'col-sm-1';
    }
    else {
        ifStatement = 'Or if'
        labelSize = 'col-sm-2';
    }

    if (InputDevice[1] == 'ON/OFF') {
        ConditionHtml =
            '<div class="col-sm-9" id="divCondition_' + removeButtonID + '" style="margin-bottom: 10px;">' +
                '<label class="control-label ' + labelSize + '">' + ifStatement + '</label>' +
                '<div class="col-sm-4">' +
                    '<input type="text" class="form-control text-center" value="' + InputDevice[0] + '" disabled>' +
                    '<input type="hidden" value="' + InputDevice[2] + '" name="input_device[]">' +
                '</div>' +
                '<div class="col-sm-2">' +
                    '<input type="text" class="form-control text-center" value="=" disabled>' +
                    '<input type="hidden" value="=" name="operator[]">' +
                '</div>' +
                '<div class="col-sm-3">' +
                    '<select class="form-control" name="condition_setpoint[]">' +
                        '<option value="1">ON</option>' +
                        '<option value="0">OFF</option>' +
                    '</select>' +
                '</div>' +
                '<button type="button" id="removeCondition" onclick="btnRemoveCondition(' + removeButtonID + ')" title="Remove">&times;</button>' +
            '</div>'
        ;
    }
    else if (InputDevice[1] == 'Temperature sensor') {
        ConditionHtml =
            '<div class="col-sm-12" id="divCondition_' + removeButtonID + '" style="margin-bottom: 10px;">' +
                '<label class="control-label ' + labelSize + '">' + ifStatement + '</label>' +
                '<div class="col-sm-3">' +
                    '<input type="text" class="form-control text-center" value="' + InputDevice[0] + '" disabled>' +
                    '<input type="hidden" name="input_device[]" value="' + InputDevice[2] + '">' +
                '</div>' +
                '<div class="col-sm-2">' +
                    '<select class="form-control" name="operator[]">' +
                        '<option value="<"> &nbsp; < </option>' +
                        '<option value="<="> &nbsp; <= </option>' +
                        '<option value="="> &nbsp; = </option>' +
                        '<option value=">"> &nbsp; > </option>' +
                        '<option value=">="> &nbsp; >= </option>' +
                    '</select>' +
                '</div>' +
                '<div class="col-sm-3">' +
                    '<input type="text" name="condition_setpoint[]" class="form-control" placeholder="Ex: 15 °C" ">' +
                '</div>' +
                '<button type="button" id="removeCondition" onclick="btnRemoveCondition(' + removeButtonID + ')" title="Remove">&times;</button>' +
            '</div>'
        ;
    }

    if (count < MaxInputs) {
        count++;
        // Add input box
        $(InputsWrapper).append(ConditionHtml);

        // Remove device selected from Input Device List
        //$("#inputDevice option[value='" + SelectedInputDevice + "']").remove();
        popover_options_list.splice(getArrayIndexForKey(popover_options_list, 'value', SelectedInputDevice), 1);

        // Destroy popover and re-initial it
        createNewPopover(popover_options_list, '#btnAddNewCondition');

        if ((MaxInputs - count) < 1) {
            $("#inputDevice").append(new Option("No more input device", "no_more"));
            $("#inputDevice").prop('disabled', true);

            // Hide popover and disable button Add new condition
            $("#btnContinue").hide();
            $("#btnAddNewCondition").prop('disabled', true);
        }
    }
}

// Remove condition from list
function btnRemoveCondition(removeButtonID) {
    // Add element back to original array
    popover_options_list[popover_options_list.length] = clone_popover_options_list[removeButtonID];
    createNewPopover(popover_options_list, '#btnAddNewCondition');

    $("#divCondition_" + removeButtonID).remove(); // Remove text box
    $("#btnAddNewCondition").prop('disabled', false); // Re-active button Add new condition

    count--; //decrement textbox count
}

// Initial popover
createNewPopover(popover_options_list, '#btnAddNewCondition');

// Some stuff to close popover
$('#btnAddNewCondition').click(function (e) {
    e.stopPropagation();
});
$(document).click(function (e) {
    if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
        $('#btnAddNewCondition').popover('hide');
    }
});
// End: Some stuff to close popover

</script>
