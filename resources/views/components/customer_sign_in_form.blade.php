<div class='row'>
        <div class="col-md-4">
            <div class="modal fade" id="modal-signin" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">

                        <div class="modal-body p-0">
                            <div class="card bg-secondary border-0 mb-0">

                                <div class="card-body px-lg-5 py-lg-5">
                                    <div class="text-center text-muted mb-4">
                                        <small>{{ __('Registration form')}}</small>
                                    </div>
                                    <form role="form" method='post' action='{{route('customer.store')}}'>
                                        @csrf
                                        <div class="form-group mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                                </div>
                                                <input class="form-control datepicker" placeholder="Departure date" name='departuredate' type="text" value="">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input class="form-control" placeholder="Family Name" name='lastname' type="text">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input class="form-control" placeholder="First Name" name='firstname' type="text">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input class="form-control" placeholder="Email" name='email' type="text">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input class="form-control" placeholder="Phone" name='phone' type="text">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <x-country-list />
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input class="form-control" placeholder="Emmergency contact" name='emergencyContact' type="text">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge input-group-alternative overlay-y-scroll">
                                                <div>
                                                    <b>Release of Liability, Waiver form of Legal Rights and Assumption of Risk</b>
                                                    <br><br>
                                                    In consideration for the renting and/or purchasing of kiteboarding equipment, and/or utilizing the facilities, ground school, kiteboarding instruction, premises, and equipment of Kite@the Nest
                                                    I hereby acknowledge and agree that:
                                                    <br><br>
                                                    1.	The participation of kiteboarding can result in mishap and even injury and I hereby RELEASE AND DISCHARGE Kite@the Nest and their officers, directors, elected officials, agents, employees, instructors, and owners of equipment and the land used for kiteboarding activities (hereinafter collectively referred to as “Released Parties”), from any and all liability, claims, demands, or causes of action that I may hereafter have for injuries, disability, death, or damages arising out of my participation in kiteboarding activities, including, but not limited to, losses caused by the negligence of the released parties.
                                                    <br><br>
                                                    2.	I understand and acknowledge that kiteboarding activities have inherent dangers that no amount of care, caution, instruction, or expertise can totally eliminate. I EXPRESSLY AND VOLUNTARILY ASSUME ALL RISK OF PERSONAL INJURY OR DEATH SUSTAINED WHILE PARTICIPATING IN KITEBOARDING ACTIVITIES WHETHER OR NOT CAUSED BY THE NEGLIGENCE OF THE RELEASED PARTIES and I further agree that I will not sue or make a claim against the Released Parties for damages or other losses sustained as a result of my participation in kiteboarding activities. I also agree to indemnify and hold the released parties harmless from all claims, judgments, and costs, including attorneys’ fees, incurred in connection with any action brought as a result of my participation in kiteboarding activities.
                                                    <br><br>
                                                    3.	I agree that I will operate the said kiteboarding equipment in a reasonable and safe manner so as not to endanger the lives of persons or property of any individual.
                                                    <br><br>
                                                    4.	I have read and understand the above and acknowledge that the same constitutes a release of liability and a waiver of my legal rights and also acknowledgment of the assumption by me of all risks arising out of my engaging in kiteboarding activities.
                                                    <br><br>
                                                    5.	I further represent that this release of liability, waiver of legal rights and assumption of risk shall continue in full force and effect for so long as I engage in kiteboarding activities which are in any way connected to or with the Released Parties.
                                                    <br><br>
                                                    6.	I further represent that I am at least 18 years of age or that as the parent or legal guardian I waive and release any and all legal rights that may accrue to me or to my minor child as the result of any injury that my son or daughter (minor) may suffer while engaging in kiteboarding activities.
                                                    <br><br>
                                                    <b>I HAVE READ THIS RELEASE OF LIABILITY, WAIVER OF LEGAL RIGHTS, AND ASSUMPTION OF RISK, FULLY UNDERSTAND ITS CONTENTS, AND SIGN IT OF MY OWN FREE WILL.
                                                    </b>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="imgData" value="" id="imgData" />
                                        <div class="h-4 text-sm text-center pt-2">SIGNATURE - <button type="button" onclick="signaturePad.clear();">Clear</button></div>
                                        <div class="text-center item-center mt-4 text-sm">
                                            <canvas id="signature-pad" class="bg-gray-100 m-auto w-auto rounded border border-gray-400" height="200" style='cursor: crosshair;'></canvas>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary my-4">{{ __('Sign-in')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
