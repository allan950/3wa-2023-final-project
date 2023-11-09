class Form {
    form;
    _is_form_submittable = false;

    constructor(form) {
        this.form = form;
    }

    isFormSubmittable() {
        return this._is_form_submittable;
    }
}

class UserForm extends Form {
    email;
    last_name;
    first_name;
    city;
    address;
    zipcode;

    constructor(form, email, last_name, first_name, address, zipcode) {
        super(form);
        this.email = email;
        this.last_name = last_name;
        this.first_name = first_name;
        this.address = address;
        this.zipcode = zipcode;
    }

    validateEmail() {
        var pattern = new RegExp(/^\S+@\S+\.[a-z]{2,3}$/);
        var isEmailLegit = pattern.test(this.email.val());

        return isEmailLegit;
    }

    verifyRequiredFields() {
        try {
            if (!this.validateEmail()) { throw "The email entered is not of the proper format"; } 
            else if (this.email.val().length < 1) { throw "Email field cannot be left empty"; } 
            else if (this.last_name.val().length < 1) { throw "Last Name field cannot be left empty"; }
            else if (this.first_name.val().length < 1) { throw "First Name field cannot be left empty"; }
            else if (this.address.val().length < 1) { throw "Address field cannot be left empty"; }
            else if (this.zipcode.val().length < 1) { throw "Zipcode field cannot be left empty"; }

            this._is_form_submittable = true;
        } catch (e) {
            const toast = createToast(e);
            $('body').append(toast);
        }
    }
}

class ProductForm extends Form {
    name;
    description;
    price;
    code;
    urlimg;
    anime;
    category;

    constructor(form, name, price, anime, category) {
        super(form);
        this.name = name;
        this.price = price;
        this.anime = anime;
        this.category = category;
    }

    verifyRequiredFields() {
        try {
            if (this.name.val().length < 1) { throw "Name field cannot be left empty"; } 
            else if (this.price.val().length < 1) { throw "Price field cannot be left empty"; }
            else if (isNaN(this.price.val())) { throw "Price field should be filled with digits only (integers or decimals)"; }
            else if (this.anime.val().length < 1 && this.anime.val() < 1) { throw "Anime field cannot be left empty"; }
            else if (this.category.val().length < 1 && this.category.val() < 1) { throw "Category field cannot be left empty"; }

            this._is_form_submittable = true;
        } catch (e) {
            const toast = createToast(e);
            $('body').append(toast);
        }
    }
}