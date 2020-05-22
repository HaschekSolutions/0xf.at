<?php

class LoginView
{
	function renderLoginForm()
	{
			$html = new HTML;

			return $html->well('<form method="POST" action="/login" class="form-horizontal">
		    <fieldset>
		        <legend>Login</legend>
		        <div class="form-group">
		            <label for="inputEmail" class="col-lg-2 control-label">Nick</label>
		            <div class="col-lg-10">
		                <input type="text" name="nick" class="form-control" id="inputEmail" value="" placeholder="Nick">
		            </div>
		        </div>
		        <div class="form-group">
		            <label for="inputPassword" class="col-lg-2 control-label">Password</label>
		            <div class="col-lg-10">
		                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
		                <div class="checkbox">
		                    <label>
		                        <input type="checkbox" name="save_sid" value="1" '.($_POST['save_sid']?'checked':'').'> Stay logged in
		                    </label>
		                </div>
		            </div>
		        </div>
		        <div class="form-group">
		            <div class="col-lg-10 col-lg-offset-2">
		                <input type="submit" class="btn btn-primary" name="submit" value="Login" />
		            </div>
		        </div>
		    </fieldset>
		</form>
		<div>
		<small>Pro tip: Don\'t forget your password. We don\'t store passwords on our servers and just decrypt your user file when you log on. We can\'t recover accounts.</small>
		</div>');
	}

	/*
	function renderForgotPW()
	{
		$html = new HTML;

		return $html->well('<form method="POST" action="/login/forgotpw" class="form-horizontal">
		    <fieldset>
		        <legend>Forgot password</legend>
		        <div class="form-group">
		        	'.$html->warning('Due to our security policy of not storing passwords we can\'t decrypt your userdata. You can reset your password but your progress will be deleted as well.','Note').'
		        </div>
		        <div class="form-group">
		            <label for="inputEmail" class="col-lg-2 control-label">Email</label>
		            <div class="col-lg-10">
		                <input type="email" name="email" class="form-control" id="inputEmail" value="" placeholder="Email">
		            </div>
		        </div>
		        <div class="form-group">
		            <div class="col-lg-10 col-lg-offset-2">
		                <input type="submit" class="btn btn-primary" name="submit" value="Reset password" />
		            </div>
		        </div>
		    </fieldset>
		</form>');
	}*/

	function renderRegisterForm()
	{
			$html = new HTML;

			return $html->well('<form method="POST" class="form-horizontal">
		    <fieldset>
		        <legend>Register</legend>

		        <div class="form-group">
		            <label for="inputFirstname" class="col-lg-2 control-label">Nick (username)</label>
		            <div class="col-lg-10">
		                <input type="text" name="nick" class="form-control" id="inputFirstname" value="" placeholder="Nickname">
		            </div>
		        </div>
		        
		        <div class="form-group">
		            <label for="inputPassword" class="col-lg-2 control-label">Password</label>
		            <div class="col-lg-10">
		                <input type="password" name="password1" class="form-control" id="inputPassword" placeholder="Password">
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="inputPassword2" class="col-lg-2 control-label">Password (repeat)</label>
		            <div class="col-lg-10">
		                <input type="password" name="password2" class="form-control" id="inputPassword2" placeholder="Password (repeat)">
		            </div>
		        </div>

				'.(defined('RECAPTCHA_KEY') && RECAPTCHA_KEY != '' ? '
		        <div class="form-group">
		        	<div class="col-lg-10">
		        		<div class="g-recaptcha" data-sitekey="'.RECAPTCHA_KEY.'"></div>
		        	</div>
				</div>
				<script src="https://www.google.com/recaptcha/api.js"></script>':'').'

		        <div class="form-group">
		            <div class="col-lg-10 col-lg-offset-2">
		                <input type="submit" class="btn btn-primary" name="submit" value="Register" />
		            </div>
		        </div>
		    </fieldset>
		</form>');
	}
}