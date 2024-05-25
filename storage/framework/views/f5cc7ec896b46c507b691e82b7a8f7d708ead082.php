<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email_id');
        const emailList = document.getElementById('emailList');
        const copyButton = document.getElementById('fillcontactInfo');
        const targetEmailInput = document.getElementById('email_id1');
        const targetEmailList = document.getElementById('emailList1');
        const hiddenEmail = document.getElementById('email_ids');
        const targetHiddenEmail = document.getElementById('email_ids1');
        
        function addExistingEmailsToList(Email,List) {
            const existingEmails = Email.value.split(',');
            existingEmails.forEach(email => {
                addEmailToList(email.trim(), List);
            });
        }
        if(hiddenEmail && hiddenEmail.value){
            addExistingEmailsToList(hiddenEmail,emailList);
        }
        if(targetHiddenEmail && targetHiddenEmail.value){
            addExistingEmailsToList(targetHiddenEmail,targetEmailList);
        }

        if(emailInput){
            emailInput.addEventListener('keydown', function(event) {
                if (event.key === 'Tab' || event.key === 'Space' || event.key === 'Enter') {
                    event.preventDefault();
                    addEmailToList(emailInput.value, emailList);
                    emailInput.value = '';
                    updateHiddenEmailInput(emailList, hiddenEmail);
                }
            });
        }
        
        if(targetEmailInput){
            targetEmailInput.addEventListener('keydown', function(event) {
                if (event.key === 'Tab' || event.key === 'Space' || event.key === 'Enter') {
                    event.preventDefault();
                    addEmailToList(targetEmailInput.value, targetEmailList);
                    targetEmailInput.value = '';
                    updateHiddenEmailInput(targetEmailList, targetHiddenEmail);
                }
            });
        }

        copyButton.addEventListener('change', function() {
            // If the checkbox is checked, copy the emails
            if (this.checked) {
                targetEmailList.innerHTML = '';

                const emails = getEmailsFromList(emailList);
                emails.forEach(email => {
                    addEmailToList(email, targetEmailList);
                });

                updateHiddenEmailInput(targetEmailList, targetHiddenEmail);
            }
        });

        function addEmailToList(email, list) {
            if (isValidEmail(email)) {
                const listItem = document.createElement('li');
                listItem.classList.add('email-item');

                const emailText = document.createElement('span');
                emailText.textContent = email;
                emailText.classList.add('email-item-text');
                listItem.appendChild(emailText);

                const removeButton = document.createElement('button');
                removeButton.innerHTML = '&times;'; // Unicode for the cross mark icon
                removeButton.classList.add('remove-btn');
                removeButton.addEventListener('click', function() {
                    listItem.remove();
                    updateHiddenEmailInput(list, hiddenEmail);
                });
                listItem.appendChild(removeButton);

                list.appendChild(listItem);
            } else {
                alert('Please enter a valid email address.');
            }
        }

        function updateHiddenEmailInput(list, input) {
            const emails = getEmailsFromList(list);
            input.value = emails.join(', ');
        }

        function getEmailsFromList(list) {
            const emailItems = list.querySelectorAll('.email-item-text');
            const emails = [];
            emailItems.forEach(item => {
                emails.push(item.textContent);
            });
            return emails;
        }

        function isValidEmail(email) {
            // You can implement your own validation logic here
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>
<?php /**PATH C:\wamp64\www\eureka\resources\views/backend/multiple-email_script.blade.php ENDPATH**/ ?>