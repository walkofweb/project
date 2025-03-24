'use strict'
const http = require('http');
const express = require('express');
const app = express();
const server = http.createServer(app);
const cors = require('cors');
const fetch = require('node-fetch');
const cookieParser = require('cookie-parser');
const path = require('path');
const fs = require('fs');
const bodyParser = require('body-parser');
var sessions = require('express-session');
var request = require('request');
const mysqlConnection = require('./config/db');
app.use(cookieParser());
app.use(cors());

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use('/assets',express.static('assets'));
app.use('/dev',express.static('dev'));
const oneDay = 1000 * 60 * 60 * 24;
app.use(sessions({
    secret: "walkofwebIntigate20230101",
    saveUninitialized:false,
    cookie: { maxAge: oneDay },
    resave: false 
}));

server.listen(3000);



// app.listen(5000);
//process.env.PORT || 
//const CLIENT_KEY = 'abc' // this value can be found in app's developer portal
app.get('/', (req, res) => {
  //res.send('Walk of Web')
   res.sendFile(path.join(__dirname,'/index.html'));
})

app.get('/user', (req, res) => {
  //res.send('Walk of Web')
  
  return res.status(200).json({
        cmd:'redirect',
        status:1,
        data:req.query,
        response_msg:'tiktok login response'
    });
})

app.get('/privacyPolicy', (req, res) => {
  //res.send('Privacy Policy')
  res.sendFile(path.join(__dirname,'/privacy_policy.html'));
})

app.get('/termCondition', (req, res) => {
  //res.send('Term Condition')
  res.sendFile(path.join(__dirname, '/terms_cond.html'));
})

app.get('/contactUs', (req, res) => {
  //res.send('Privacy Policy')
  res.sendFile(path.join(__dirname,'/contactus.html'));
})

var sendEmailContactUs = function(object){   
   return new Promise(function(resolve, reject) {
     request(object, function (error, response) {
      if (error) throw new Error(error);
      if (!error){            
        resolve(1);
      }else{
        resolve(0);
      } 
    });
     
   
    });

  //
}

app.post('/save_contactUs', async(req, res) => {
 
   const request = req.body ;
   const name = request.name ;
   const phone = request.phone ;
   const email = request.email ;
   const subject = request.subject ;
   const message = request.message ;


 var contactUsMailRequest = {
    'method': 'POST',
    'url': 'https://dev.walkofweb.net/api/v1/sendContactUsEmail',
    'headers': { },
    formData: {
      'name':name,
      'phone':phone,
      'email':email,
      'subject':subject,
      'message':message
      }
    };

   const query="INSERT INTO contactus (name,phone_number,email,subject,message)VALUES('"+name+"', '"+phone+"', '"+email+"', '"+subject+"', '"+message+"');";
    mysqlConnection.query(query,async function(err,results){
    if(!err){      
      var emailInfo= await sendEmailContactUs(contactUsMailRequest);     
      res.send('succ');
    } else {
       res.send('fail');       
    }
    });
   
})


app.post('/authCallback', (req, res) => {   
      const request = req.body ;
fs.writeFile("test", "'"+JSON.stringify(request)+"'", function(err) {
    if(err) {
        return console.log(err);
    }
    console.log("The file was saved!");
}); 

return res.status(200).json({
        cmd:'redirect',
        status:1,
        data:req.body,
        response_msg:'tiktok login  response'
    });

})

function isEmpty(object) {  
  return Object.keys(object).length === 0
}




var getUserId = function(encryption_key){

   return new Promise(function(resolve, reject) {
     //conversationId
      var check_user="select id from users where encryption='"+encryption_key+"' and isTrash=0";
      mysqlConnection.query(check_user,function(err,results){
    if(!err && results.length > 0){
      
      resolve(results[0].id);
    } else {
       resolve(0);
       
    }
    });
});
  //
}

var queryExecute = function(query){

   return new Promise(function(resolve, reject) {
     //conversationId      
      mysqlConnection.query(query,function(err,results){
    if(!err && results.length > 0){
      
      resolve(results);
    } else {
       resolve(0);       
    }
    });
});
  //
}

var getTikTokAccessToken = function(options){

   return new Promise(function(resolve, reject) {
    
    request(options, function (error, response) {
      //throw new Error(error);
      if (!error){
        var resp=JSON.parse(response.body);         
        resolve(resp.data.access_token);
      }else{
        resolve(0);
      } 
    });

});
  //
}

var getTikTokUserData = function(options){

   return new Promise(function(resolve, reject) {
    
    request(options, function (error, response) {
      //throw new Error(error);
      if (!error){
        var resp=JSON.parse(response.body);         
        resolve(resp.data.user);
      }else{
        resolve(0);
      } 
    });

});
  //
}


var getTikTokVideoData = function(options){

   return new Promise(function(resolve, reject) {
    
    request(options, function (error, response) {
      //throw new Error(error);
      if (!error){
        var resp=JSON.parse(response.body);         
        resolve(resp.data);
      }else{
        resolve(0);
      } 
    });

});
  //
}

app.get('/authCallback',async(req, res) => {

 //const userState = req.session.user_state;
 const reqState = req.query.state ;
 const TIKTOK_CLIENT_KEY ='aw2j809lnifzn891' ;
 const TIKTOK_CLIENT_SECRET = 'bd12f2950da989f8381d5c845c9dfd29' ;
 const code = req.query.code;

  var userId = await getUserId(reqState);
  if(userId==0){
       res.send('Invalid Request');
  }
   
 var options = {
  'method': 'POST',
  'url': 'https://open-api.tiktok.com/oauth/access_token/',
  'headers': {
  },
  formData: {
    'client_key':TIKTOK_CLIENT_KEY,
    'client_secret':TIKTOK_CLIENT_SECRET,
    'code': code,
    'grant_type': 'authorization_code'
  }
};


var access_token = await getTikTokAccessToken(options);

if(access_token==undefined){
   res.send('Invalid Request');
}

 var options1 = {
    'method': 'GET',
    'url': 'https://open.tiktokapis.com/v2/user/info/?fields=open_id,union_id,avatar_url,follower_count,following_count,likes_count,bio_description,display_name',
    'headers': {
    'Authorization': 'Bearer '+access_token
    },
    formData: {

    }
    };

 var optionsV = {
    'method': 'POST',
    'url': 'https://open.tiktokapis.com/v2/video/list/?fields=id,create_time,cover_image_url,share_url,video_description,duration,height,width,title,embed_html,embed_link,like_count,comment_count,share_count,view_count',
    'headers': {
    'Authorization': 'Bearer '+access_token
    },
    formData: {
    }
    };
    console.log('token'+access_token);
var userData = await getTikTokUserData(options1);
var videoData = await getTikTokVideoData(optionsV);
console.log('End');
console.log('videoData1'+JSON.stringify(videoData));
// console.log('following_count'+ userData.follower_count );
// console.log('following_count'+ userData.following_count );
// console.log('likes_count'+ userData.likes_count );
 

var query_1 ="INSERT INTO `social_media_log` (`userId`,`type`,`socialData`)VALUES ("+userId+",'tiktok','"+JSON.stringify(userData)+"')";
var query_2 = "select id from social_info where user_id="+userId+" and social_type=4";
var query_3 = "update social_info set `follows_count` = "+userData.following_count+", `followers_count` = "+userData.follower_count+", `likes_count` = "+userData.likes_count+" where user_id="+userId+" and social_type=4" ;
var query_4 = "insert into social_info (`user_id`,`type`,`social_type`,`social_id`,`follows_count`,`followers_count`,`likes_count`)VALUES("+userId+",'Tiktok',4,'"+userData.union_id+"',"+userData.following_count+","+userData.follower_count+","+userData.likes_count+")"   ;
await queryExecute(query_1);
var checkTikTokUsr = await queryExecute(query_2);

if(checkTikTokUsr!=0){
  var updateinfo=await queryExecute(query_3);
  res.redirect('https://www.walkofweb.net/success');
   //res.send('Successful updated tiktok data'); 
}else{
  var updateinfo=await queryExecute(query_4); 
  res.redirect('https://www.walkofweb.net/success');
  //res.send('Successful add tiktok data');
}
   
return true ;

});

app.get('/success', (req, res) => {
  res.send('Successful updated tiktok data'); 
});

app.get('/auth', (req, res) => {
    
    const CLIENT_KEY='aw2j809lnifzn891';
    //console.log('state'+req.query.state);
    var state='6c20bc57a46267086688d5e0f0241175';

    
    const createCsrfState = () => Math.random().toString(36).substring(7);
    const csrfState = createCsrfState(); 
    res.cookie('csrfState', csrfState, { maxAge: 60000 });
 
    //req.session.user_state=csrfState+'_15';
    
    //console.log('csrfState'+csrfState);
    let url = 'https://open-api.tiktok.com/platform/oauth/connect/';

    url += `?client_key=${CLIENT_KEY}`;
    url += '&scope=user.info.basic,video.list';
    url += '&response_type=code';    
    url += `&redirect_uri=${encodeURIComponent('https://www.walkofweb.net/authCallback')}`;
    url += '&state='+state;
     
    res.redirect(url);
})