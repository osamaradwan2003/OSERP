/**
 * Dialog Support Module
 * Handles modal dialog functionality for action links
 */
(function (dialog_support, $) {
    var btn_id, dialog_ref;

    dialog_support.hide = function () {
        dialog_ref && dialog_ref.close();
    };

    dialog_support.clicked_id = function () {
        return btn_id;
    };

    dialog_support.init = function (selector) {
        var buildDialogConfig = function (event) {
            var buttons = [];
            var dialog_class = "modal-dlg";

            $(this)
                .attr("class")
                .split(/\s+/)
                .forEach(function (className) {
                    var width_class = className.split("modal-dlg-");
                    if (width_class.length > 1) {
                        dialog_class = width_class[0] === "modal" ? className : dialog_class;
                    }
                });

            var hasNewBtn = "btnNew" in $(this).data();

            $.each($(this).data(), function (name, value) {
                var btnParts = name.split("btn");
                if (btnParts.length > 1) {
                    var btnName = btnParts[1].toLowerCase();
                    var isSubmit = btnName === "submit";
                    var isNew = btnName === "new";
                    var isEnter = hasNewBtn ? isNew : isSubmit;

                    buttons.push({
                        id: btnName,
                        label: value,
                        cssClass: { submit: "btn-primary", delete: "btn-danger" }[btnName] || "btn-default",
                        hotkey: isEnter ? 13 : undefined,
                        action: function (dlog_ref) {
                            btn_id = btnName;
                            dialog_ref = dlog_ref;
                            var form = $("form", dlog_ref.$modalBody).first();
                            var validator = form.data("validator");
                            var submitted = validator && validator.formSubmitted;

                            if (btnName === "submit" && !submitted && btn_id !== "btnNew") {
                                form.submit();
                                if (validator && validator.valid()) {
                                    $("#submit").prop("disabled", true).css("opacity", 0.5);
                                }
                            }
                            return false;
                        },
                    });
                }
            });

            if (buttons.length === 0) {
                buttons.push({
                    id: "close",
                    label: lang.line("common_close"),
                    cssClass: "btn-primary",
                    action: function (dialog_ref) {
                        dialog_ref.close();
                    },
                });
            }

            return {
                buttons: buttons.sort(function (a, b) {
                    return $(b).text() < $(a).text() ? -1 : 1;
                }),
                cssClass: dialog_class,
            };
        };

        var showDialog = function (event) {
            var $link = $(event.target).closest("a, button");
            BootstrapDialog.show({
                title: $link.attr("title"),
                message: function () {
                    var node = $("<div></div>");
                    $.get($link.attr("href") || $link.data("href"), function (data) {
                        node.html(data);
                    });
                    return node;
                },
            });
            return false;
        };

        $(selector)
            .off("click")
            .on("click", function (event) {
                $.extend(buildDialogConfig.call(this, event), showDialog(event));
            });
    };

    dialog_support.submit = function (button_id) {
        return function (dlog_ref) {
            btn_id = button_id;
            dialog_ref = dlog_ref;
            return false;
        };
    };
})((window.dialog_support = window.dialog_support || {}), jQuery);

/**
 * Table Support Module
 * Handles bootstrap table functionality for data grids
 */
(function (table_support, $) {
    var options;
    var table = function () {
        return $("#table").data("bootstrap.table");
    };

    var selected_rows = function () {
        return $("#table td input:checkbox:checked").parents("tr");
    };

    var row_selector = function (id) {
        return "tr[data-uniqueid='" + id + "']";
    };

    var rows_selector = function (ids) {
        ids = ids instanceof Array ? ids : ("" + ids).split(":");
        return ids.map(function (element) {
            return row_selector(element);
        });
    };

    var enable_actions = function (callback) {
        return function () {
            var hasSelection = selected_rows().length > 0;

            // $("#toolbar button:not(.dropdown-toggle):not(#toggle_deleted)")
            //     .prop("disabled", !hasSelection)
            //     .toggleClass("disabled", !hasSelection);

            // $("#toggle_deleted").prop("disabled", false).removeAttr("disabled");

            if (typeof callback === "function") {
                callback();
            }
        };
    };

    var highlight_row = function (id, color) {
        $(rows_selector(id)).each(function (index, element) {
            var original = $(element).css("backgroundColor");
            $(element)
                .find("td")
                .animate({ backgroundColor: color || "#e1ffdd" }, "slow")
                .animate({ backgroundColor: color || "#e1ffdd" }, 5000)
                .animate({ backgroundColor: original }, "slow");
        });
    };

    var do_action = function (action) {
        return function (url, ids) {
            if (confirm($.fn.bootstrapTable.defaults.formatConfirmAction(action))) {
                $.post(
                    (url || options.resource) + "/" + action,
                    { "ids[]": ids || selected_ids() },
                    function (response) {
                        if (response.success) {
                            var selector = ids ? row_selector(ids) : selected_rows();
                            table().collapseAllRows();

                            $(selector).each(function (index, element) {
                                $(this)
                                    .find("td")
                                    .animate({ backgroundColor: "green" }, 1200)
                                    .end()
                                    .animate({ opacity: 0 }, 1200, function () {
                                        table().remove({
                                            field: options.uniqueId,
                                            values: selected_ids(),
                                        });

                                        if (index === $(selector).length - 1) {
                                            refresh();
                                            enable_actions()();
                                        }
                                    });
                            });

                            $.notify(response.message, { type: "success" });
                        } else {
                            $.notify(response.message, { type: "danger" });
                        }
                    },
                    "json",
                );
            }
            return false;
        };
    };

    var toggle_column_visibility = function () {
        if (localStorage[options.employee_id]) {
            var user_settings = JSON.parse(localStorage[options.employee_id]);
            if (user_settings[options.resource]) {
                Object.entries(user_settings[options.resource]).forEach(function (entry) {
                    if (entry[1]) {
                        table().showColumn(entry[0]);
                    } else {
                        table().hideColumn(entry[0]);
                    }
                });
            }
        }
    };

    var load_success = function (callback) {
        return function (response) {
            if (typeof options.load_callback === "function") {
                options.load_callback();
                options.load_callback = undefined;
            }
            dialog_support.init("a.modal-dlg");
            if (typeof callback === "function") {
                callback.call(this, response);
            }
        };
    };

    var init_delete = function () {
        $("#delete").click(function () {
            do_action("delete")();
        });
    };

    var init_restore = function () {
        $("#restore").click(function () {
            do_action("restore")();
        });
    };

    var init_toggle_deleted = function () {
        var btn = document.getElementById("toggle_deleted");
        if (btn) {
            btn.removeAttribute("disabled");

            // Watch for external code re-adding disabled attribute
            if (!btn.dataset.observerAttached) {
                btn.dataset.observerAttached = "true";
                new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.attributeName === "disabled") {
                            btn.removeAttribute("disabled");
                        }
                    });
                }).observe(btn, { attributes: true });
            }
        }
    };

    var init = function (_options) {
        options = _options;
        var enhancedEnableActions = enable_actions(options.enableActions);
        var enhancedLoadSuccess = load_success(options.onLoadSuccess);

        var exportSuffix = new Date()
            .toISOString()
            .slice(0, 16)
            .replace(/(-|\s|T|:)*/g, "");

        $("#table")
            .addClass("table-striped table-bordered")
            .bootstrapTable(
                $.extend({}, options, {
                    columns: options.headers,
                    stickyHeader: true,
                    url: options.resource + "/search",
                    sidePagination: "server",
                    selectItemName: "btSelectItem",
                    pagination: true,
                    search: options.resource || false,
                    showColumns: true,
                    clickToSelect: true,
                    showExport: true,
                    exportDataType: "basic",
                    exportTypes: ["json", "xml", "csv", "txt", "sql", "excel", "pdf"],
                    exportOptions: {
                        fileName: options.resource.replace(/.*\/(.*?)$/, "$1") + "_" + exportSuffix,
                    },
                    onPageChange: function (response) {
                        enhancedLoadSuccess(response);
                        enhancedEnableActions();
                    },
                    onCheck: enhancedEnableActions,
                    onUncheck: enhancedEnableActions,
                    onCheckAll: enhancedEnableActions,
                    onUncheckAll: enhancedEnableActions,
                    onLoadSuccess: function (response) {
                        enhancedLoadSuccess(response);
                        enhancedEnableActions();
                        init_toggle_deleted();
                    },
                    onColumnSwitch: function (field, checked) {
                        var userSettings = JSON.parse(localStorage[options.employee_id] || "{}");
                        userSettings[options.resource] = userSettings[options.resource] || {};
                        userSettings[options.resource][field] = checked;
                        localStorage[options.employee_id] = JSON.stringify(userSettings);
                        dialog_support.init("a.modal-dlg");
                    },
                    queryParamsType: "limit",
                    iconSize: "sm",
                    silentSort: true,
                    paginationVAlign: "bottom",
                    escape: true,
                }),
            );

        enhancedEnableActions();
        init_delete();
        init_restore();
        toggle_column_visibility();
        dialog_support.init("button.modal-dlg");
        init_toggle_deleted();

        if (options.resource && options.resource.includes("cashflow")) {
            $("#table").find('input[type="checkbox"]').prop("checked", false);
        }
    };

    var refresh = function () {
        table().refresh();
    };

    var submit_handler = function (url) {
        return function (resource, response) {
            if (!response.success) {
                $.notify(response.message, { type: "danger" });
                return false;
            }

            var id = String(response.id || "");
            var selector = rows_selector(response.id);
            var rows = $(selector.join(",")).length;

            if (rows > 0 && rows < 15) {
                var ids = id.split(":");
                $.get(
                    [url || resource + "/row", id].join("/"),
                    {},
                    function (response) {
                        selector.forEach(function (element) {
                            var rowId = $(element).data("uniqueid");
                            table().updateByUniqueId({
                                id: rowId,
                                row: response[rowId] || response,
                            });
                        });
                        dialog_support.init("a.modal-dlg");
                        highlight_row(ids);
                    },
                    "json",
                );
            } else {
                options.load_callback = function () {
                    enhancedEnableActions();
                    highlight_row(id);
                };
                refresh();
            }

            $.notify(response.message, { type: "success" });
            return false;
        };
    };

    var handle_submit = submit_handler();

    $.extend(table_support, {
        init: init,
        refresh: refresh,
        selected_ids: function () {
            return table()
                .getSelections()
                .map(function (element) {
                    var id = element[options.uniqueId || "id"];
                    return id !== "-" ? id : null;
                })
                .filter(function (id) {
                    return id !== null;
                });
        },
        do_delete: do_action("delete"),
        do_restore: do_action("restore"),
        submit_handler: function (url) {
            this.handle_submit = submit_handler(url);
        },
        handle_submit: handle_submit,
    });
})((window.table_support = window.table_support || {}), jQuery);

/**
 * Form Support Module
 * Handles form validation and submission
 */
(function (form_support, $) {
    form_support.error = {
        errorClass: "has-error",
        errorLabelContainer: "#error_message_box",
        wrapper: "li",
        highlight: function (e) {
            $(e).closest(".form-group").addClass("has-error");
        },
        unhighlight: function (e) {
            $(e).closest(".form-group").removeClass("has-error");
        },
    };

    form_support.handler = $.extend({}, form_support.error, {
        submitHandler: function (form) {
            $(form).ajaxSubmit({
                success: function (response) {
                    $.notify(response.message, {
                        type: response.success ? "success" : "danger",
                    });
                },
                dataType: "json",
            });
        },
        rules: {},
        messages: {},
    });
})((window.form_support = window.form_support || {}), jQuery);

/**
 * Number sorter for table columns
 * Parses numbers from formatted strings for sorting
 */
function number_sorter(a, b) {
    a = +String(a).replace(/[^\-0-9]+/g, "");
    b = +String(b).replace(/[^\-0-9]+/g, "");
    return a - b;
}
