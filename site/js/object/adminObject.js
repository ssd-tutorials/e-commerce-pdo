var adminObject = {
    clickReplace : function(thisIdentity) {
        "use strict";
        $(document).on('click', thisIdentity, function(e) {
            e.preventDefault();
            var thisObj = $(this);
            var thisUrl = thisObj.data('url');
            $.getJSON(thisUrl, function(data) {
                if (data && !data.error) {
                    if (!systemObject.isEmpty(data.replace)) {
                        thisObj.replaceWith(data.replace);
                    }
                }
            });
        });
    },
    clickCallReload : function(thisIdentity) {
        "use strict";
        $(document).on('click', thisIdentity, function(e) {
            e.preventDefault();
            var thisUrl = $(this).data('url');
            $.getJSON(thisUrl, function(data) {
                if (data && !data.error) {
                    window.location.reload();
                }
            });
        });
    },
    clickYesNoSingle : function(thisIdentity) {
        "use strict";
        $(document).on('click', thisIdentity, function(e) {
            e.preventDefault();
            var thisObj = $(this);
            var thisValue = thisObj.data('value');
            if (parseInt(thisValue, 10) === 0) {
                var thisGroup = thisObj.data('group');
                var thisGroupItems = $('[data-group="' + thisGroup + '"]');
                var thisUrl = thisObj.data('url');
                $.getJSON(thisUrl, function(data) {
                    if (data && !data.error) {
                        $.each(thisGroupItems, function() {
                            $(this).text('No');
                            $(this).attr('data-value', 0);
                        });
                        thisObj.text('Yes');
                        thisObj.attr('data-value', 1);
                    }
                });
            }
        });
    },
    clickRemoveRowTemplate : function(id, span, url) {
        "use strict";
        var thisTemp = '<tr id="clickRemove-' + id + '">';
        if (span > 1) {
            thisTemp += '<td colspan="' + span + '">';
        }
        thisTemp += '<div class="fl_r">';
        thisTemp += '<a href="#" data-url="' + url;
        thisTemp += '" class="clickRemoveRow mrr5">Yes</a>';
        thisTemp += '<a href="#" class="clickRemoveRowConfirm">No</a>';
        thisTemp += '</div>';
        thisTemp += '<span class="warn">Are you sure you wish to remove this records?<br />';
        thisTemp += 'There is no undo!</span>';
        thisTemp += '</td>';
        thisTemp += '</tr>';
        return thisTemp;
    },
    clickAddRowConfirm : function(thisIdentity) {
        "use strict";
        $(document).on('click', thisIdentity, function(e) {
            e.preventDefault();
            var thisObj = $(this);
            var thisParent = thisObj.closest('tr');
            var thisId = thisParent.attr('id').split('-')[1];
            var thisSpan = thisObj.data('span');
            var thisUrl = thisObj.data('url');
            if ($('#clickRemove-' + thisId).length === 0) {
                thisParent.before(adminObject.clickRemoveRowTemplate(thisId, thisSpan, thisUrl));
            }
        });
    },
    clickRemoveRowConfirm : function(thisIdentity) {
        "use strict";
        $(document).on('click', thisIdentity, function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });
    },
    clickRemoveRow : function(thisIdentity) {
        "use strict";
        $(document).on('click', thisIdentity, function(e) {
            e.preventDefault();
            var thisObj = $(this);
            var thisId = thisObj.closest('tr').attr('id').split('-')[1];
            var thisUrl = thisObj.data('url');
            $.getJSON(thisUrl, function(data) {
                if (data && !data.error) {
                    if (!systemObject.isEmpty(data.replace)) {
                        $.each(data.replace, function(k, v) {
                            $(k).html(v);
                        });
                    } else {
                        $('#row-' + thisId).remove();
                        thisObj.closest('tr').remove();
                    }
                }
            });
        });
    },
    clickHideShow : function(thisIdentity) {
        "use strict";
        $(document).on('click', thisIdentity, function(e) {
            e.preventDefault();
            var thisTarget = $(this).data('show');
            $(this).hide();
            $(thisTarget).show().focus();
        });
    },
    blurUpdateHideShow : function(thisIdentity) {
        "use strict";
        $(document).on('focusout', thisIdentity, function(e) {
            var thisObj = $(this);
            var thisForm = thisObj.closest('form');
            thisForm.find('.warn').remove();
            var thisUrl = thisForm.data('url');
            var thisId = thisObj.data('id');
            var thisShow = thisObj.attr('id');
            var thisValue = thisObj.val();
            if (!systemObject.isEmpty(thisValue)) {
                $.post(thisUrl + thisId, { id : thisId, value : thisValue }, function(data) {
                    if (data && !data.error) {
                        thisObj.hide();
                        $('[data-show="#' + thisShow + '"]').text(thisValue).show();
                    }
                }, 'json');
            } else {
                thisObj.before('<p class="warn">Please provide a value</p>');
            }
        });
    },
    sortRows : function(obj) {
        "use strict";
        obj.find('tr').livequery(function() {
            var thisObj = $(this).parent('tbody');
            $.each(thisObj, function() {
                var thisTbody = $(this);
                var thisUrl = thisTbody.data('url');
                if (!systemObject.isEmpty(thisUrl)) {
                    thisTbody.tableDnD({
                        onDrop : function(thisTable, thisRow) {
                            var thisArray = $.tableDnD.serialize();
                            $.ajax({
                                type : 'POST',
                                url : thisUrl,
                                data : thisArray
                            });
                        }
                    });
                }
            });
        });
    },
    submitAjax : function() {
        "use strict";
        $(document).on('submit', 'form.ajax', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var thisForm = $(this);
            thisForm.find('.warn').remove();
            var thisArray = thisForm.serializeArray();
            var thisUrl = thisForm.data('action');
            if (!systemObject.isEmpty(thisUrl)) {
                $.post(thisUrl, thisArray, function(data) {
                    if (data) {
                        if (!data.error) {
                            if (!systemObject.isEmpty(data.replace)) {
                                $.each(data.replace, function(k, v) {
                                    $(k).html(v);
                                });
                                thisForm[0].reset();
                            } else {
                                window.location.reload();
                            }
                        } else if (!systemObject.isEmpty(data.validation)) {
                            $.each(data.validation, function(k, v) {
                                $('.' + k).append(v);
                            });
                        }
                    }
                }, 'json');
            }
        });
    },
    selectRedirect : function(thisIdentity) {
        "use strict";
        $('form').on('change', thisIdentity, function(e) {
            var thisSelected = $('option:selected', this);
            var thisUrl = thisSelected.data('url');
            if (!systemObject.isEmpty(thisUrl)) {
                window.location.href = thisUrl;
            }
        });
    }
};
