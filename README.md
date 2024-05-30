# eureka

SAP like system for your business !!!

## Description

This is the whole SAP system where you can generate purchase order and sale order and records will be available in invetory reports (sales , purchase and per day inventory report). You can see your transactions also and every modue will have some doc number generated automatically using series number and academic year. you can rectify your inventory also using rectification module and transfer bin-bin or warehouse-warehouse. we have claims module also which has flow of arppove/reject/revise data within multiple role of users.

## Getting Started

### Dependencies

* None

### Installing
* Clone the repository: git clone https://github.com/Shady-shaikh/eureka.git
* Navigate to the project directory: cd eureka

### Site Details

* Login page
* ![Login Page](https://shady-shaikh.github.io/portfolio_usama/projects/eureka%20(1).png)
* Business Partner Master Details
* ![Bp Master](https://shady-shaikh.github.io/portfolio_usama/projects/eureka%20(2).png)
* Logs
* ![Logs](https://shady-shaikh.github.io/portfolio_usama/projects/eureka%20(3).png)
* Form Example
* ![Po Form](https://shady-shaikh.github.io/portfolio_usama/projects/eureka%20(4).png)
* Item Addition
* ![Product add](shady-shaikh.github.io/portfolio_usama/projects/eureka%20(5).png)
* Daily Report
* ![report](https://shady-shaikh.github.io/portfolio_usama/projects/eureka%20(6).png)
* Bin transfer Module
* ![bin transfer](https://shady-shaikh.github.io/portfolio_usama/projects/eureka%20(7).png)
* Cliam
* ![claim](https://shady-shaikh.github.io/portfolio_usama/projects/eureka%20(8).png)


### Executing program

* You should have php installed before moving further
* make sure to clone this repo inside www/ht docs depeding on wamp/xamp
* make sure your server is running
* you need to create database named as eureka then import this file there https://github.com/Shady-shaikh/eureka/blob/main/public/eureka_db.sql
* after installing open this project in vs code and change your database file credentials which you can find in config/database.php
* then search on any browser http://localhost/eureka/

## Usage

* login with creds superadmin@gmail.com and Pass@123
* you can create users , business partners , products , warehouse/bins and much more in master
* in order to add items in inventory you need to create po then forward that po to gr using + (clone) button once its done you can see items getting reflected in inventory
* You can create mulitple companies from localhost/eureka/admin/company and login with that company email and default password (Pass@123)
* After login with that company you can now create po's,gr's and sale order then order fulfilment and ar invoice
* You can rectify your inventory using inventory rectification module found in inventory tab
* You can trasnfer your data from bin-bin and warehouse-warehouse
* You can return your items using sales return and credit note can be also added
* A Distributor and Sub-Distributor role user can raise claim and it will be verified according to the flow like (Distributor->Channel (channel wise)->Head->Finance)
* A claim can be approve/reject or revise by any of superior role users which is ther in flow
* Make sure to enter proper details according to hul and enjoy...


## Authors

* Abu Osama Shaikh
* [LinkedIn](https://www.linkedin.com/in/usama-shaikh-81294a306/)
* usashaikh86@gmail.com

Feel free to contribute or add more data to enhance the project.


