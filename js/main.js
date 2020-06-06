
    $('body').on('click', '.password-checkbox', function(){
        if ($(this).is(':checked')){
            $('#password-input').attr('type', 'text');
        } else {
            $('#password-input').attr('type', 'password');
        }
    });

    $('.show').click(function (e) {
        e.preventDefault();

        if($('.msg-err').hasClass('none')){
            $('.msg-err').removeClass('none');
        } else {
            $('.msg-err').addClass('none');
        }
    });

    $('.none1').click(function (e) {
        e.preventDefault();

        if($('.msg-suc').hasClass('none')){
            $('.msg-suc').removeClass('none');
        } else {
            $('.msg-suc').addClass('none');
        }
    });

    $(function(){
        //Получить элемент, к которому необходимо добавить маску
        $("#phone").mask("8(999) 999-9999");
    });



    /*
    Регистрация
    */
    $('.register-btn').click(function (e) {
        e.preventDefault();
        $(`input`).removeClass('error');

        let p_number = $('input[name="p-number"]').val(),
            surname = $('input[name="surname"]').val(),
            name = $('input[name="name"]').val(),
            mid_name = $('input[name="mid-name"]').val(),
            phone = $('input[name="phone"]').val(),
            email = $('input[name="email"]').val(),
            pass = $('input[name="pass"]').val(),
            pass_confirm = $('input[name="pass-confirm"]').val(),
            pos = $('input[name="position"]').val(),
            sub = $('select[name="subdivision"]').val();



        let formData = new FormData();
        formData.append('p-number',p_number);
        formData.append('surname',surname);
        formData.append('name',name);
        formData.append('mid-name',mid_name);
        formData.append('phone',phone);
        formData.append('email', email);
        formData.append('pass',pass);
        formData.append('pass-confirm',pass_confirm);
        formData.append('position',pos);
        formData.append('subdivision',sub);


        $.ajax({
            url: 'back/signup.php',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success (data) {
                if(data.status){
                    document.location.href = '/profile.php';
                } else {
                    if(data.type === 1){
                        data.fields.forEach(function (field) {
                            $(`input[name="${field}"]`).addClass('error');
                            $(`select[name="${field}"]`).addClass('error');
                        });
                    }
                        $('.msg-err').removeClass('none').text(data.message);
                }
            }
        });
    });
    /*
Авторизация
*/
    $('.signin-btn').click(function (e) {
        e.preventDefault();
        $(`input`).removeClass('error');

        let email = $('input[name="email"]').val(),
            pass = $('input[name="pass"]').val();

        let formData = new FormData();
        formData.append('email', email);
        formData.append('pass',pass);



        $.ajax({
            url: 'back/signin.php',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success (data) {
                if(data.status){
                    document.location.href = '/profile.php';
                } else {
                    if(data.type === 1){
                        data.fields.forEach(function (field) {
                            $(`input[name="${field}"]`).addClass('error');
                        });
                    }
                    $('.msg-err').removeClass('none').text(data.message);
                }
            }
        });
    });

