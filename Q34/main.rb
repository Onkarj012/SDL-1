require 'dotenv'
require 'mail'

Dotenv.load

Mail.defaults do
  delivery_method :smtp, {
    address:              'smtp.gmail.com',
    port:                 587,
    user_name:            ENV['GMAIL_USERNAME'],
    password:             ENV['GMAIL_APP_PASSWORD'],
    authentication:       :login,
    enable_starttls_auto: true
  }
end

message = Mail.new do
  from    ENV['GMAIL_USERNAME']
  to      ENV['RECIPIENT_EMAIL']
  subject 'Hello from Ruby!'
  body    'This is a test email sent using Ruby + Gmail App Password.'
end

message.deliver!

puts "Email sent successfully!"