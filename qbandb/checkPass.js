(function() {
    var password1 = document.getElementById('password');
    var password2 = document.getElementById('retypepassword');
    var phone = document.getElementById('phonenum');
    var credit = document.getElementById('credit');
    var expiry = document.getElementById('expiry');
    var cvv = document.getElementById('cvv');
    
    var checkPasswordValidity = function() {
        if (password1.value != password2.value) {
            password2.setCustomValidity('passwords must match');
        } else {
            password2.setCustomValidity('');
        }        
    };

    var checkPhone = function(){
        if(isNaN(phone.value) || phone.value.length !=10) {
            phone.setCustomValidity('please enter a valid phone number');
        } else {
            phone.setCustomValidity('');
        }   

    };
    var checkCredit = function(){
        if(isNaN(credit.value) || credit.value.length !=16) {
            credit.setCustomValidity('please enter a valid credit card number');
        } else {
            credit.setCustomValidity('');
        }   

    };
      var checkExpiry = function(){
        if(isNaN(expiry.value) || expiry.value.length !=4) {
            expiry.setCustomValidity('please enter a valid expiry date');
        } else {
            expiry.setCustomValidity('');
        }   

    };
      var checkcvv = function(){
        if(isNaN(cvv.value)||cvv.value.length!=3) {
            cvv.setCustomValidity('please enter a valid security code');
        } else {
            cvv.setCustomValidity('');
        }   

    };
  
    password2.addEventListener('change', checkPasswordValidity, false);
    phone.addEventListener('change', checkPhone, false);
    credit.addEventListener('change', checkCredit, false);
    expiry.addEventListener('change', checkExpiry, false);
    cvv.addEventListener('change', checkcvv, false);
    
    var form = document.getElementById('registerform');
    form.addEventListener('submit', function(event) {
        checkPasswordValidity();
        if (!this.checkValidity()) {
            event.preventDefault();
            password2.focus();
        }
        checkPhone();
        if (!this.checkValidity()){
            event.preventDefault();
            phone.focus();
        }
        checkCredit();
        if (!this.checkValidity()){
            event.preventDefault();
            credit.focus();
        }
        checkExpiry();
        if (!this.checkValidity()){
            event.preventDefault();
            expiry.focus();
        }
        checkcvv();
        if (!this.checkValidity()){
            event.preventDefault();
            cvv.focus();
        }
    }, false);
}());