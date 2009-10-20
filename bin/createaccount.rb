#!/usr/bin/ruby

require 'mysql'
require 'net/smtp'

$smtp_server       = 'localhost'
$mail_from         = 'registration@themanaworld.org'
$mail_from_name    = 'The ManaWorld team'
$mail_subject      = 'The Mana World Account registration'
$mail_body_success = "Your account was created successfully. Have fun playing themanaworld.\n"
$mail_body_error   = "The was something wrong with the creation of your account.\n" + 
                     "Error message: "
$mysql_hostname    = "localhost"
$mysql_database    = "test"
$mysql_username    = "test"
$mysql_password    = "test123"                     
$create_script     = "/path/to/script"

##############################################################################

returns = [
	{'message' => "successfully created",        'status' => :SUCCESS, 'final_state' => 1 },		
	{'message' => "Same account already exists", 'status' => :FAILED, 'final_state' => 2 },
	{'message' => "Email is too short",          'status' => :FAILED, 'final_state' => 2 },
	{'message' => "Email is too long",           'status' => :FAILED, 'final_state' => 2 },
	{'message' => "Invalid email",               'status' => :FAILED, 'final_state' => 2 },
	{'message' => "Account name is too short",   'status' => :FAILED, 'final_state' => 2 },
	{'message' => "Account name is too long",    'status' => :FAILED, 'final_state' => 2 },
	{'message' => "Illegal character",           'status' => :FAILED, 'final_state' => 2 },
	{'message' => "command not found",           'status' => :FAILED, 'final_state' => 2 },
]

##############################################################################

def send_mail(email, username, status, errm)
	message = "From: #{$mail_from_name} <#{$mail_from}>\n" +
		"To: #{username} <#{email}>\n" +
		"Subject: #{$mail_subject}\n\n"

	if status == :SUCCESS then
		message << $mail_body_success
	elsif status == :FAILED then
		message << $mail_body_error << errm
	end

	Net::SMTP.start($smtp_server) do |smtp|
  		smtp.send_message(message, $mail_from, email)
	end
end

##############################################################################

db = Mysql::new($mysql_hostname, $mysql_username, $mysql_password, $mysql_database)
db.query("SELECT id, username, password, email, gender 
          FROM   tmw_accounts 
          WHERE  state = 0").each do |id, username, password, email, g|
	begin
		gender = case g.to_i
				when 1 then "M"
				when 2 then "F"
			  end

		# insert the right command here
		retval = `#{$create_script} create #{username} #{gender} #{email} #{password}`
		# parse the return value
		returns.each do |retcode|
			if retval.include? retcode['message'] then
				send_mail( email, username, retcode['status'], retcode['message'] )
				db.query("UPDATE tmw_accounts SET STATE = #{retcode['final_state']} WHERE id = #{id}")
			end 			
		end
	rescue
		puts "ERROR occured"
		puts $!
		db.query("UPDATE tmw_accounts SET STATE = 2 WHERE id = #{id}")	
	end
end
db.close

