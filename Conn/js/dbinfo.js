const mysql=require('mysql');//데이터베이스 연결

var connection ={
  host     : '218.38.29.99',
  database : 'monkeyvpn_db',
  user     : 'monkeyvpn',
  password : 'monkeyvpn2020!@#$',
  charset  : 'utf8',
  dateStrings: 'date'
};



var pool= mysql.createPool(connection);

    // pool.getConnection(function(err,connection){
    //     connection.query("쿼리",function(err,rows){
    //     //rows를 처리할 내용
    //     //release를 해주어 커넥션이 pool로 되돌아 갈 수 있도록 해줍니다.
    //     connection.release();
    //     //이제 이 커넥션은 pool로 돌아가 다른 주체가 사용 할 수 있도록 준비합니다.
    //     });
    // });

module.exports = pool;
//추가사항 : 이렇게 해줘도 끊기는 현상이 발생 할 때가 있었습니다.
//이건 최후의 방법인데 특정 시간마다 연결했다 끊는겁니다.
//저는 결국 이 방법으로 해결했습니다.

function keepAlive(){
   pool.getConnection(function(err, connection){
     if(err) { return; }
     console.log("connection reBorn");
     connection.ping();
     connection.release();
   });

    //redis client를 사용중이라면, 아마 Redis연결도 빠르게 끊길겁니다.
    //client.ping();  // 라고 해주면 Redis연결도 유지합니다.
 }
 setInterval(keepAlive, 60*1000);
