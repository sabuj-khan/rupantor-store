<div class="login_register_wrap section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-10">
                <div class="login_wrap">
                    <div class="padding_eight_all bg-white">
                        <div class="heading_s1">
                            <h3>Verification</h3>
                        </div>
                            <div class="form-group mb-3">
                                <input id="code" type="text" required="" class="form-control" name="email" placeholder="OTP Code">
                            </div>
                            <div class="form-group mb-3">
                                <button onclick="verifyOTP()" type="submit" class="btn btn-fill-out btn-block" name="verify">Confirmation</button>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    async function verifyOTP(){
        let code = document.getElementById("code").value;
        let email = sessionStorage.getItem('email');

        if(code.length === 0){
            alert('Code is required');
        }else{
            $(".preloader").delay(90).fadeOut(100).removeClass('loaded');
            let response = await axios.get('/verifyloginotp/'+email+'/'+code);

            if(response.status === 200 && response.data['status'] === 'success'){
               if(sessionStorage.getItem("last_location")){
                    window.location.href=sessionStorage.getItem("last_location")
               }else{
                    window.location.href='/';
               }
            }else{
                $(".preloader").delay(90).fadeOut(100).addClass('loaded');
                alert("Request Fail")
            }

        }
    }


</script>