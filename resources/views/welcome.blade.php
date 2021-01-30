<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Houzeo Test</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
		
        <!-- Styles -->
        <style>
            .content-white {
			  font-family: helvetica;
			  color: #0a0a0a;
			  text-align: center;
			}


			.content-white .container label {
			  color: #0a0a0a;
			}

			.content-white .container input {
			  font-size: 16px;
			  padding: 0 .75em;
			  border: 1px solid #ccc;
			  color: #0a0a0a;
			  height: 3em;
			  box-sizing: border-box;
			  width: 100%;
			  margin-top: .5em;
			}

			.content-white .container input:disabled {
			  background-color: #eee;
			  color: #999;
			}

			.content-white .container .input-container {
			  margin: 0 auto 2em;
			  width: 60%;
			}
			
			.input-container button {
				margin-top:10px;
				padding:10px 20px;
				background-color:#013dc3;
				border:1px solid #013dc3;
				color:#d8d1d1;
				font-size:15px;
				cursor:pointer;
			}
			
			.content-white .container .autocomplete-menu {
			  overflow-y: scroll;
			  max-height: 13em;
			  box-shadow: 0 7px 7px rgba(0, 0, 0, 0.12);
			  color: #7d7d7d;
			  position: absolute;
			  text-align: left;
			  width: inherit;
			  z-index: 10;
			}

			.content-white .container .autocomplete-menu li div {
			  padding: .75em;
			}

			.content-white .container .autocomplete-menu b {
			  color: #0a0a0a;
			}

			.content-white .container .autocomplete-menu .ui-menu-item-wrapper {
			  padding-left: 1em;
			}

			.content-white .container .labels {
			  display: inline-block;
			  font-weight: bold;
			  width: 40%;
			}

			.content-white .container .data {
			  display: inline-block;
			  padding-left: 1em;
			  width: 50%;
			}

			.content-white .docs-pricing-links {
			  font-weight: bold;
			  margin-top: 2em;
			}

			.inline {
			  display: inline-block;
			  vertical-align: top;
			  width: 40%;
			}

			.align-right {
			  text-align: right;
			}

			.align-left {
			  text-align: left;
			}
			
			.alert {
			  padding: 20px;
			  background-color: #f44336;
			  color: white;
			}
			
			.success {
			  padding: 20px;
			  background-color: green;
			  color: white;
			}

			.closebtn {
			  margin-left: 15px;
			  color: white;
			  font-weight: bold;
			  float: right;
			  font-size: 22px;
			  line-height: 20px;
			  cursor: pointer;
			  transition: 0.3s;
			}

			.closebtn:hover {
			  color: black;
			}

        </style>
    </head>
    <body>
        <div class="content-white">
			<div class="container" id="alert">
				<form id="create_user">
					<div class="input-container">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="text" name="name" placeholder="Enter Name"/>
						<input type="email" name="email" placeholder="Enter Email"/>
						<input type="text" name="contact" placeholder="Enter Contact No"/>
						<input id="address-input" name="address" placeholder="Enter Address" autocomplete="smartystreets"/>
						<ul class="autocomplete-menu" style="display:none;"></ul>
						<div class="disabled-inputs">
							<input id="city" name="city" placeholder="City" disabled />
							<div class="state-and-zip">
								<input id="state" name="state" placeholder="State" disabled />
								<input id="zip" name="zip" placeholder="Zip*" disabled />
							</div>
						</div>
						
						<button type="button" class="create_user">Submit</button>
					</div>
				</form>
			</div>
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="//d79i1fxsrar4t.cloudfront.net/jquery.liveaddress/5.2/jquery.liveaddress.min.js"></script>
		
		<script>
			jQuery.LiveAddress({
				key: "11482000361076971", 
				addresses: [{
					address1: '#address-input',
					locality: '#city',
					administrative_area: '#state',
					postal_code: '#zip',
				}]
			});
			
			$(document).on('click','.create_user', function(){
				var $this = $(this);
				$this.attr('disabled',true);
				$.ajax({
					type: "POST",
					url: '/create-user',
					dataType: 'json',
					data : $('#create_user').serialize(),
					cache: false,
					error:function(e){
						if(e.responseJSON.error == true){
							$this.attr('disabled',false);
							var error = '';
							$.each(e.responseJSON.message, function(key, value){
								error += '<li>'+value+'</li>';
							});
							$('#alert').prepend('<div class="alert">'+
											  '<span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span>'+
											  '<ul>'+error+'</ul>'+
											'</div>');
											
							setTimeout(function(){ $('.alert').remove() }, 2000);
						}
					},
					success: function(response){
						if (response.success == true) {
							$this.attr('disabled',false);
							$('input[name="name"]').val('');
							$('input[name="email"]').val('');
							$('input[name="contact"]').val('');
							$('input[name="address"]').val('');
							$('#city').val('');
							$('#state').val('');
							$('#zip').val('');
							
							$('#alert').prepend('<div class="success">'+
											  '<span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span>'+
											  '<ul>'+response.message+'</ul>'+
											'</div>');
											
							setTimeout(function(){ $('.success').remove() }, 5000);

						}
					 }
				});
			})
		</script>
    </body>
</html>
