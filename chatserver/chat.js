// ロボットの返答内容
const chat = [
    'Hello ! Welcome to AI chat !',
    'What is your name ?',
    'How are you today ?',
    [['Alright !'], ['Oh really!'], ['Ok!']]// ランダムな返答
];


// ロボットの返信の合計回数（最初は0）
// これを利用して、自分が送信ボタンを押したときの相手の返答を配列から指定する
let chatCount = 0;


// 画面への出力
// valはメッセージ内容，personは誰が話しているか
function output(val, person) {
    // 一番下までスクロール
    const field = document.getElementById('field');
    field.scroll(0, field.scrollHeight-field.clientHeight);
  
    const ul = document.getElementById('chat-ul');
    const li = document.createElement('li');
    // このdivにテキストを指定
    const div = document.createElement('div');
    div.textContent = val;
    
    if (person === 'me') { // 自分
        div.classList.add('chat-right');
        li.classList.add('right');
        ul.appendChild(li);
        li.appendChild(div);
    }else if (person === 'robot') { // 相手
	
        // ロボットが2個連続で返信してくる時、その間は返信不可にする
        // なぜなら、自分の返信を複数受け取ったことになり、その全てに返信してきてしまうから
        // 例："Hi!〇〇!"を複数など
        // （今回のロボットの連続返信は2個以内とする）
        chatBtn.disabled = true;
        setTimeout( ()=> {
            chatBtn.disabled = false;
            li.classList.add('left');
            div.classList.add('chat-left');
            ul.appendChild(li);
            li.appendChild(div);
            // ロボットのトークの合計数に1足す
            chatCount++;
        }, 2000); 
    }
}


const chatBtn = document.getElementById('chat-button');
const inputText = document.getElementById('chat-input');


// 送信ボタンを押した時の処理
function btnFunc() {
    if (!inputText.value) return false;
    // 自分のテキストを送信
    output(inputText.value, 'me');
  
    setTimeout( ()=> {
        // 入力内を空欄にする
        // 一瞬の間でvalueを取得し、ロボットの"Hi!〇〇!"の返信に利用
        // 送信ボタンを押した瞬間にvalueを消したら、やまびこに失敗した
        inputText.value = '';
    }, 1);
    //シナリオのCSVファイルから回答を取得してhitしたものをボタン形式で表示する(クリックすると内容が表示される)
    var segmenter = new TinySegmenter();                 // インスタンス生成
    var segs = segmenter.segment(inputText.value);  // 単語の配列が返る
    //FAQのCSVファイル内を探してHITするものを表する。
    var req = new XMLHttpRequest(); // HTTPでファイルを読み込むためのXMLHttpRrequestオブジェクトを生成
    var arr=[];
    req.open("get", "FAQ.csv", false); // アクセスするファイルを指定
    req.send(null); // HTTPリクエストの発行
    arr=req.responseText.split('\n');
    for(var i2=0;i2<segs.length;++i2){
	if(segs[i2].length>1){
	    for(var i=0;i<arr.length-1;i++){
	        if(arr[i].indexOf(segs[i2]) !== -1) {
   		    temp=arr[i].split(',')[0]+"："+arr[i].split(',')[1]
	            output(temp,'robot');
                    arr[i]='';
                }
	    }
	    //return 0;
	}
    }
}


//CSVファイルを読み込む関数getCSV()の定義
function getCSV(){
    var req = new XMLHttpRequest(); // HTTPでファイルを読み込むためのXMLHttpRrequestオブジェクトを生成
    req.open("get", "FAQ.csv", true); // アクセスするファイルを指定
    req.send(null); // HTTPリクエストの発行
	x
    // レスポンスが返ってきたらconvertCSVtoArray()を呼ぶ	
    req.onload = function(){
	convertCSVtoArray(req.responseText); // 渡されるのは読み込んだCSVデータ
    }
}
 
// 読み込んだCSVデータを二次元配列に変換する関数convertCSVtoArray()の定義
function convertCSVtoArray(str){ // 読み込んだCSVデータが文字列として渡される
    var result = []; // 最終的な二次元配列を入れるための配列
    var tmp = str.split("\n"); // 改行を区切り文字として行を要素とした配列を生成
 
    // 各行ごとにカンマで区切った文字列を要素とした二次元配列を生成
    for(var i=0;i<tmp.length-1;++i){
        result[i] = tmp[i].split(',');
        output(result[i],'robot');
    }
 
}

// 最初にロボットから話しかけられる
setTimeout( ()=> {
    output('質問を送信してください。', 'robot');
}, 1000);