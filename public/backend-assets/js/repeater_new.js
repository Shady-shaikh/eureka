jQuery.fn.extend({
    createRepeater: function (options = {}) {


        var hasOption = function (optionKey) {
            return options.hasOwnProperty(optionKey);
        };

        var option = function (optionKey) {
            return options[optionKey];
        };

        var generateId = function (string) {
            return string
                .replace(/\[/g, '_')
                .replace(/\]/g, '')
                .toLowerCase();
        };

        var addItem = function (items, key, fresh = true) {
            var itemContent = items;
            // console.log('itemContent',itemContent);
            var group = itemContent.data("group");
            console.log('group1',group);
            if(group == undefined){
                group = 'invoice_items';
                console.log('group2',group);
            }
            // var test = items.data("test");
            // console.log('group',group);
            // console.log('test',test);
            var item = itemContent;
            var input = item.find('input,select,textarea');
            
            console.log('key',key);
            
            input.each(function (index, el) {
                var attrName = $(el).data('name');
                var skipName = $(el).data('skip-name');
                // console.log('attrName',attrName);
                
                if (skipName != true) {
                    $(el).attr("name", group + "[" + key + "]" + "[" + attrName + "]");
                    // $(el).attr("name", "invoice_items[" + key + "]" + "[" + attrName + "]");
                } else {
                    if (attrName != 'undefined') {
                        $(el).attr("name", attrName);
                    }
                }
                console.log('name',$(el).attr("name"));
                if (fresh == true) {
                    $(el).attr('value', '');
                }

                $(el).attr('id', generateId($(el).attr('name')));
                $(el).parent().find('label').attr('for', generateId($(el).attr('name')));
            })

            var itemClone = items;

            /* Handling remove btn */
            var removeButton = itemClone.find('.remove-btn');
            // alert(itemClone);
            if (key == 0) {
                removeButton.attr('disabled', true);
            } else {
                removeButton.attr('disabled', false);
            }

            removeButton.attr('onclick', '$(this).parents(\'.items\').remove()');

            var newItem = $("<div class='items'>" + itemClone.html() + "<div/>");
            newItem.attr('data-index', key)

            newItem.appendTo(repeater);
        };

        /* find elements */
        var repeater = this;
        var items = repeater.find(".items");
        var key = 0;
        var addButton = repeater.find('.repeater-add-btn');
        // console.log(addButton);

        items.each(function (index, item) {
         
            items.remove();

            if (hasOption('showFirstItemToDefault') && option('showFirstItemToDefault') == true) {

                addItem($(item), key);
                key++;
            } else {
                if (items.length > 1) {
                    addItem($(item), key);
                    key++;
                }
            }
        });

        /* handle click and add items */
        addButton.off('click').on("click", function (e) {
            addItem($(items[0]), key);
            key++;
        });
    }
});
