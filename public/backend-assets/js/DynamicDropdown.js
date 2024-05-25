//Created by usama- common dependent-dropdown

//route -> enter route to redirect
// id -> enter your value
// append_id -> enter id where u want to append options
// prev_id -> incase not using laravel from input then we can use this as old data
// relatedDropdowns -> you can pass ids of input fields which u want to reset (child of child field )


class DynamicDropdown {
    constructor(route, id, append_id, prev_id=null, ...relatedDropdowns) {
        this.route = route;
        this.id = id;
        this.append_id = append_id;
        this.prev_id = prev_id;
        this.relatedDropdowns = relatedDropdowns;

        this.initialize();
    }

    initialize() {
        const self = this;

        // Function to clear options of related dropdowns
        function clearRelatedDropdowns() {
            self.relatedDropdowns.forEach(function (dropdown) {
                $(dropdown)
                    .empty()
                    .append('<option value="">Please Select</option>');
            });
        }

        $.get(
            this.route,
            {
                id: self.id,
            },
            function (data) {
                // Update Sales Officer dropdown options
                $(self.append_id)
                    .empty()
                    .append('<option value="">Please Select</option>');
                $.each(data, function (key, value) {
                    if (self.prev_id != null && self.prev_id == key) {
                        $(self.append_id).append(
                            '<option value="' +
                                key +
                                '" selected>' +
                                value +
                                "</option>"
                        );
                    } else {
                        $(self.append_id).append(
                            '<option value="' + key + '">' + value + "</option>"
                        );
                    }
                });

                clearRelatedDropdowns();
            }
        );
    }
}
