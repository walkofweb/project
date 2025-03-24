const mysql = require('mysql');

var db_config = {
  localAddress:"walkofwebdb.cqsjvannfj21.eu-central-1.rds.amazonaws.com",
	host:"walkofwebdb.cqsjvannfj21.eu-central-1.rds.amazonaws.com",
	user:"admin",
	password:"oxy3VYU1itEY0gX7siYG",
	database:"walkofweb_dev",
	multipleStatements: true
};





// sports_book_
var mysqlConnection;
 mysqlConnection = mysql.createConnection(db_config);


mysqlConnection.connect((err)=>{
	if(!err){
		console.log("db connection succeeded.");
	}else{
		console.log("db connection failed \n Error :"+ JSON.stringify(err,undefined,2));
	}
});

function handleDisconnect() {
  console.log('h');
  mysqlConnection = mysql.createConnection(db_config);
                                                  

  mysqlConnection.connect(function(err) {              // The server is either down
    if(err) {                                     // or restarting (takes a while sometimes).
      console.log('error when connecting to db:', err);
      setTimeout(handleDisconnect, 2000); // We introduce a delay before attempting to reconnect,
    }                                     // to avoid a hot loop, and to allow our node script to
  });                                     // process asynchronous requests in the meantime.
                                          // If you're also serving http, display a 503 error.
  mysqlConnection.on('error', function(err) {
    console.log('db error', err);
    if(err.code === 'PROTOCOL_CONNECTION_LOST') { // Connection to the MySQL server is usually
      handleDisconnect();                         // lost due to either server restart, or a
    } else {                                      // connnection idle timeout (the wait_timeout
      throw err;                                  // server variable configures this)
    }
  });
}

handleDisconnect();

module.exports = mysqlConnection ;
