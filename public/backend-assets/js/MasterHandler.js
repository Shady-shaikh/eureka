// Define a class for handling the master functionality
class MasterHandler {
    constructor(inputField, addModal, submitBtn, route, role = null, ...params) {
        this.inputField = inputField;
        this.addModal = addModal;
        this.submitBtn = submitBtn;
        this.route = route;
        this.role = role;
        this.params = params;

        this.initialize();
    }

    initialize() {
        const self = this;
        let modalOpenBound = false;

            

        $(document).on('click', '.open_modal', function () {
            // alert(data_id);
            var data_id = $(this).data('id');
            // console.log(data_id);
            $(self.inputField).select2('close');
            if(data_id == '#add_area_modal'){
                $('#add_beat_modal').modal('hide');
            }else if(data_id == '#add_route_modal'){
                $('#add_beat_modal').modal('hide');
            }
            else{
                $(data_id).modal('show');
            }
   
        });

        $('.btn_cut').click(function(){
            $('#add_area_modal').modal('hide');
            $('#add_route_modal').modal('hide');
            $('#add_beat_modal').modal('show');

        });

        
    

        $(self.submitBtn).on('click', function () {
        
            // ... your existing code for handling form submission ...
            var data = [];
            // Push non-null values from the params array
            self.params.forEach(function(param) {
                if (param !== null) {
                    data.push($(param, $(self.addModal)).val());
                }
            });
            var nonEmptyElementFound = data.every(function(element) {
                return element !== null && element !== undefined && element.trim() !== "";
            });

            // Instead of alerting, you can display messages in a more user-friendly way.
            if (nonEmptyElementFound) {
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                // Handle success and failure messages
                $.ajax({
                    url: self.route,
                    type: 'post',
                    data: {
                        data_name: data,
                        role:self.role,
                        _token: csrfToken,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.flag == 'success') {
                            $(self.inputField).html(data['data']);
                            $(self.addModal).modal('hide');
                            alert(data['message']);
                        } else {
                            alert(data['message']);
                            $(self.addModal).modal('hide');
                        }
                    }
                });
                $(self.addModal).modal('hide');
            } else {
                // Handle empty fields
                alert('Please Fill Required Fields');
            }
        });
     

        $(self.inputField).on('select2:open', function () {
            let brandOptions = $(this).data('select2');
 
                if (!modalOpenBound) {
                    modalOpenBound = true;

                brandOptions.$results.parents('.select2-results')
                    .append(`<div class="m-2 select2-link2 select2-close text-right"><button class="btn btn-secondary open_modal " data-id="${self.addModal}">Add New Item +</button></div>`);

                      // Bind the click event only once
                    $(document).on('click', '.open_modal', function () {
                        var data_id = $(this).data('id');
                        $(self.inputField).select2('close');
                        $(data_id).modal('show');
                        console.log(data_id);
                    });
                }
           
        });
    }

   
    
}

