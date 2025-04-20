# 33.Install Ruby Environment Setup and Write a Ruby program which accept
# the user's first and last name and print them in reverse order with a space
# between them.

require 'sinatra'

get '/' do
  erb :index
end

post '/reverse' do
  @first_name = params[:first_name]
  @last_name  = params[:last_name]
  @reversed_name = "#{@last_name} #{@first_name}"
  erb :reverse
end

