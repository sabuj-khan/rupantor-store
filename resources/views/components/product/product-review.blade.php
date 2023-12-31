<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-tab-pane" type="button" role="tab" aria-controls="review-tab-pane" aria-selected="false">Review</button>
                    
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="review_create-tab" data-bs-toggle="tab" data-bs-target="#review_create-tab-pane" type="button" role="tab" aria-controls="review_create-tab-pane" aria-selected="false">Add Review</button>
                </li>

            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                   <div id="p_details">

                   </div>
                </div>
                <div class="tab-pane fade" id="review-tab-pane" role="tabpanel" aria-labelledby="review-tab" tabindex="0">
                    <ul id="reviewList" class="list-group list-group-flush">

                    </ul>
                </div>


                <div class="tab-pane fade" id="review_create-tab-pane" role="tabpanel" aria-labelledby="review_create-tab" tabindex="0">
                    <label class="form-label">Write Your Review</label>
                    <textarea class="form-control form-control-sm" id="reviewTextID" rows="5" placeholder="Your Review"></textarea>
                    <label class="form-label mt-2">Rating Score</label>
                    <input min="1" value="0" max="10" id="reviewScore" type="number" class="form-control-sm form-control">
                    <button onclick="AddReview()" class="btn btn-danger mt-3 btn-sm">Submit</button>
                </div>


            </div>
        </div>
        <hr class="mt-5">
    </div>
    
</div>


<script>

    getProductDetails();

    async function getProductDetails(){
        let urlParams = new URLSearchParams(window.location.search);
        let id = urlParams.get('id');

        let response = await axios.get('/productdetails/'+id);
        $(".preloader").delay(700).fadeOut(700).addClass('loaded');

        document.getElementById('p_details').innerText=response.data['data']['des'];

    }


    getProductReview();

    async function getProductReview(){
        let urlParams = new URLSearchParams(window.location.search);
        let id = urlParams.get('id');

        let response = await axios.get('/productreview/'+id);
        $("#reviewList").empty();
        $(".preloader").delay(700).fadeOut(700).addClass('loaded');

        response.data['data'].forEach((item, index) => {
            let each = `<li class="list-group-item">
                <h6>${item['profile']['cus_name']}</h6>
                <p class="m-0 p-0">${item['description']}</p>
                <div class="rating_wrap">
                    <div class="rating">
                        <div class="product_rate" style="width:${parseFloat(item['rating'])}%"></div>
                    </div>
                </div>
            </li>`

            $("#reviewList").append(each);
        });
    }



    async function AddReview(){
        let urlParams = new URLSearchParams(window.location.search);
        let id = urlParams.get('id');

        let reviewTextID = document.getElementById("reviewTextID").value;
        let reviewScore = document.getElementById("reviewScore").value;

        if(reviewTextID.length === 0){
            alert("Review Comments Required !")
        }else if(reviewScore.length === 0){
            alert("Score Required !")
        }else{
            $(".preloader").delay(90).fadeIn(100).removeClass('loaded');
            let reviewData = {
                "description":reviewTextID,
                "rating":reviewScore,
                "product_id":id
            }

            let response = await axios.post("/createProductReview", reviewData);
           
            $(".preloader").delay(700).fadeOut(700).addClass('loaded');

            if(response.status === 200 && response.data['message'] === 'success'){
                alert('Review added successfully');
                await getProductReview();

            }else{
                alert("Something went wrong from review")
            }
        }


    }

</script>