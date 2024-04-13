<script>
export default {
	// リアルタイムバリデーション
	watch: {
		// メッセージ
		newMessage: {
			handler: function () {
				// テキストエリアの高さ変更
				this.resizeNewMessageArea();
				// if(this.newMessage === ""){
				// 	this.height = '50px';
				// }
				
				this.errors.newMessage = [];

				// 正規表現パターンを定義(改行とスペース以外の文字を含む)
				const regex = /.*[ぁ-んァ-ヶー一-龯0-9０-９a-zA-Z!?_+*'"`#$%&\-^\\@;:,./=~|[\](){}<>¥！-／：-＠［-｀｛-～、-〜”’・￥].*/;

				// メッセージがある場合
				if(this.newMessage){
					// console.log('メッセージがある場合');

					// メッセージが改行とスペース以外の文字を含まない場合
					if(!regex.test(this.newMessage)){
						this.errors.newMessage.push('メッセージを入力してください。');
						// console.log('メッセージが改行とスペース以外の文字を含まない場合');

					// メッセージが1000文字以上の場合
					}else if(this.newMessage.length > 1000){
						this.errors.newMessage.push('メッセージは、1000文字以下で入力してください。');
						// console.log('メッセージが1000文字以上の場合');
					}

				// メッセージがない場合
				}else{
					// console.log('メッセージがない場合');
				}

				// エラーがなければメッセージ投稿ボタンが押せるようになる
				if(this.newMessage && !this.getErrorMessage(this.errors.newMessage)){
					this.isDisabled = false;
				}else{
					this.isDisabled = true;
				}
			},
			immediate: true,
		},
	},
}
</script>
