@extends('layouts.app')

@section('content')
<div class="right_col" role="main">
   <div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Your Account</h2>

					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
						<div class="profile_img">
							<!-- end of image cropping -->
							<div id="crop-avatar">
								<!-- Current avatar -->
								<img alt="Avatar" class="img-responsive avatar-view" src="{{ defaultProfile(Auth::user()->profile_picture) }}" title="Change the avatar" data-toggle="modal" data-target="#cropdialog"> <!-- Cropping modal -->
								<div id="cropdialog" aria-hidden="true" aria-labelledby="avatar-modal-label" class="modal fade" id="avatar-modal" role="dialog" tabindex="-1">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">

											{!! \Form::open(['url' => route('cms-account-upload-image'), 'files' => true, 'method' => 'POST', 'class' => 'avatar-form']) !!}
												<div class="modal-header">
													<button class="close" data-dismiss="modal" type="button">×</button>
													<h4 class="modal-title" id="avatar-modal-label">Change Avatar</h4>
												</div>
												<div class="modal-body">
													<div class="avatar-body">
														<!-- Upload image and data -->
														<div class="avatar-upload">

															<input class="avatar-src" name="avatar_src" type="hidden">
															<input class="avatar-data" name="avatar_data" type="hidden"> <label for="avatarInput">Local upload</label>
															<input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
														</div><!-- Crop and preview -->
														<div class="row" style="display:none;">
															<div class="col-md-9">
																<div class="avatar-wrapper"><img src="{{ defaultProfile(Auth::user()->profile_picture) }}"></div>
															</div>
															<div class="col-md-3">
																<div class="avatar-preview preview-lg"></div>
																<div class="avatar-preview preview-md"></div>
																<div class="avatar-preview preview-sm"></div>
															</div>
														</div>
														<div class="row avatar-btns">
															<div class="col-md-9" style="display:none;">
																<div class="btn-group">
																	<button class="btn btn-primary" data-method="rotate" data-option="-90" title="Rotate -90 degrees" type="button">Rotate Left</button> <button class="btn btn-primary" data-method="rotate" data-option="-15" type="button">-15deg</button> <button class="btn btn-primary" data-method="rotate" data-option="-30" type="button">-30deg</button> <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button">-45deg</button>
																</div>
																<div class="btn-group">
																	<button class="btn btn-primary" data-method="rotate" data-option="90" title="Rotate 90 degrees" type="button">Rotate Right</button> <button class="btn btn-primary" data-method="rotate" data-option="15" type="button">15deg</button> <button class="btn btn-primary" data-method="rotate" data-option="30" type="button">30deg</button> <button class="btn btn-primary" data-method="rotate" data-option="45" type="button">45deg</button>
																</div>
															</div>
															<div class="col-md-3">
																<button class="btn btn-primary btn-block avatar-save" type="submit">Done</button>
															</div>
														</div>
													</div>
												</div><!-- <div class="modal-footer">
                                                    <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                                                  </div> -->
											{!! \Form::close() !!}
										</div>
									</div>
								</div><!-- /.modal -->
								<!-- Loading state -->
								<div aria-label="Loading" class="loading" role="img" tabindex="-1"></div>
							</div><!-- end of image cropping -->
						</div>
						<p><small>(Click on the image above to change your profile picture)</small></p>
						<h3>{{ Auth::user()->user_display_name }}</h3>
						
						<br />
						<br />
						<h4>Mobile Login QR Code:</h4>
						<p>Scan the image below using a QR Code scanner on your phone to login easily for 2 weeks</p>
						{!! \App\Http\Controllers\Auth\AuthController::mobileLoginQRCode(200) !!}
					</div>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<div class="profile_title">
							<div class="col-md-6">
								<h2>Linked Social Accounts</h2>
							</div>
						</div><!-- start of user-activity-graph -->
						<div class="social-connections">
							@if( !isset($providers) || ! collect($providers)->contains('facebook'))
							  <div class="col-sm-2">
							@else
							  <div class="col-sm-2 active">
							@endif
							    <a href="{{ route('add-social-auth', ['provider' => 'facebook']) }}" class="facebook">
							      <i class="fa fa-facebook fa-3x"></i>
							      <span>Facebook</span>
							    </a>
							  </div>

							@if( !isset($providers) || ! collect($providers)->contains('twitter'))
							  <div class="col-sm-2">
							@else
							  <div class="col-sm-2 active">
							@endif
							    <a href="{{ route('add-social-auth', ['provider' => 'twitter']) }}" class="twitter">
							      <i class="fa fa-twitter fa-3x"></i>
							      <span>Twitter</span>
							    </a>
							  </div>

							@if( !isset($providers) || ! collect($providers)->contains('github'))
							  <div class="col-sm-2">
							@else
							  <div class="col-sm-2 active">
							@endif
							    <a href="{{ route('add-social-auth', ['provider' => 'github']) }}" class="github">
							      <i class="fa fa-github fa-3x"></i>
							      <span>Github</span>
							    </a>
							  </div>
							
							@if( !isset($providers) || ! collect($providers)->contains('google'))
							  <div class="col-sm-2">
							@else
							  <div class="col-sm-2 active">
							@endif
							    <a href="{{ route('add-social-auth', ['provider' => 'google']) }}" class="google">
							      <i class="fa fa-google fa-3x"></i>
							      <span>Google</span>
							    </a>
							  </div>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->

@stop