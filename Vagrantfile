# -*- mode: ruby -*-
# vi: set ft=ruby :

project_name = "granitecms"
ip_address = "172.22.22.30"

# Begin our configuration using V2 of the API
Vagrant.configure(2) do |config|

  config.vm.box = "scotch/box"
  config.vm.box_check_update = true

  # Configuration for our virtualisation provider
  config.vm.provider "VirtualBox" do |vb|
     # Memory (RAM) capped at 1024mb
    vb.customize ["modifyvm", :id, "--memory", "1024"]
  end

  # Give our new VM a fake IP Address and domain name
  # To utilise this, add the following to your /etc/hosts file
  #   172.22.22.30 granitecms.dev
  config.vm.define project_name do |node|
    node.vm.hostname = project_name + ".dev"
    node.vm.network :private_network, ip: ip_address
  end

  # Sync the containing folder to the web directory of the VM
  #   The sync will persist as you edit files, you won't have
  #   to destroy and re-up the VM each time you make a change
  #
  config.vm.synced_folder "./", "/var/www", :owner=> 'www-data', :group=>'www-data', :mount_options=> ["dmode=777,fmode=775"]
  config.vm.synced_folder "./public", "/var/www/html", :owner=> 'www-data', :group=>'www-data', :mount_options=> ["dmode=777,fmode=775"]
  config.vm.synced_folder "./storage", "/var/www/storage", :owner=> 'www-data', :group=>'www-data', :mount_options=> ["dmode=777,fmode=777"]

  config.vm.provision "shell", inline: <<-SHELL


    # Create our database and give root all permissions
    mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS #{project_name};"
    mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root';"
    sudo service mysql restart

    sudo usermod -a -G www-data vagrant
  
    # Move to the web directory
    cd /var/www
    
    if [ ! -d "/var/www/vendor" ]; then
      mkdir /var/www/vendor 

      # Install the application
      composer install
      
      # Copy default dev details
      cp .env.example .env

      # Generate a unique application key
      php artisan key:generate
    else
      composer update
    fi
    
    if [ ! -d "/var/www/storage" ]; then
      mkdir /var/www/storage
    fi

    if [ ! -d "/var/www/storage/framework/views" ]; then
      mkdir /var/www/storage/framework
      mkdir /var/www/storage/framework/views
      mkdir /var/www/storage/framework/sessions
    fi

    chmod -R 777 storage
    chmod -R 777 bootstrap/cache

    # Install the database and seed it with sample data
    php artisan migrate --seed
    
    # ~~~~~~ The following is optional and can be run ~~~~~~ 
    # ~~~~~~ locally instead of through the VM        ~~~~~~ 

    ## Install our NPM dependencies locally
    # npm install gulp laravel-elixir

    ## Process the styling for the website
    # gulp

  SHELL
end