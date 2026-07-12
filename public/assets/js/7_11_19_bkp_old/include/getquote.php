<div class="page-content gradient_1" id="application-form">

        <div class="container">

            <div class="row">

                <div class="heading-title text-center m-b-10">

                    <h3 class="text-uppercase text-white carsue ">We love to hear from you, Get a Quote !</h3>

                    <span class="text-uppercase text-white">We'll get back to you soon.</span>

                </div>

                <div class="col-md-8">

                    <form action="inquiry_action.php" method="post" class="contact-comments m-top-50" >



                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="form-field-name">Name *</label>

                                <input type="text" name="name" id="form-field-name" class="form-control" maxlength="100" required="" data-error="You must enter name">

                                <div class="help-block with-errors"></div>
                            </div>



                            <div class="col-md-6 form-group">
                                <label for="form-field-email">Email *</label>

                                <input type="email" name="email" id="form-field-email" class="form-control" maxlength="100" required="" data-error="Invalid email address!">

                                <div class="help-block with-errors"></div>
                            </div>



                            <div class="form-group col-md-6">
                                <label for="form-field-phone">Phone</label>

                                <input type="text" name="phone" id="form-field-phone" class="form-control" maxlength="100">
                            </div>



                            <div class="form-group col-md-6">
                                <label for="form-field-subject">Subject</label>

                                <input type="text" name="subject" id="form-field-subject" class="form-control" maxlength="100">
                            </div>



                            <div class="form-group col-md-12">
                                <label for="form-field-comments">Brief Message</label>

                                <textarea name="comments" id="form-field-comments" class="cmnt-text form-control" rows="6" maxlength="400"></textarea>
                            </div>

                            <div class="form-group col-md-12">

                                <button type="submit" name="submit" class="btn btn-small btn-info pull-right">Send Message</button>

                            </div>
                        </div>
                        <input type="hidden" name="id" value="FORM_ALT">
                    </form>
                </div>

                <div class="col-md-4">
                    <img src="assets/img/promo_v02.png" class="p-t-50">
                </div>

            </div>
        </div>
    </div>