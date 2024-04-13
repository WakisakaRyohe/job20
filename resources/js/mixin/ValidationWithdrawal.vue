<script>
export default {
	data(){
		return {
			isInput: true,
		}
	},

	// リアルタイムバリデーション
	watch: {
		// 退会希望
		request: {
			handler: function () {
				this.errors.request = [];

				// 退会希望にチェックがない場合
				if(!this.request){
					this.errors.request.push('退会希望にチェックを入れてください。');
					// console.log('退会希望にチェックがない場合');
				}
			},
			immediate: true,
		},

		// 退会理由
		reason: {
			handler: function () {
				this.errors.reason = [];

				// 退会理由が未選択の場合
				if(!this.reason){
					this.errors.reason.push('退会理由は選択必須です。');
					// console.log('退会理由が未選択の場合');
				}
			},
			immediate: true,
		},

		// ご意見・ご要望
		withdrawal_message: {
			handler: function () {
				// テキストエリアの高さ変更
				this.resizeMessageArea();
				// エラー配列を空にする
				this.errors.withdrawal_message = [];
				// 正規表現パターンを定義(改行とスペース以外の文字を含む)
				const regex = /.*[ぁ-んァ-ヶー一-龯0-9０-９a-zA-Z!?_+*'"`#$%&\-^\\@;:,./=~|[\](){}<>¥！-／：-＠［-｀｛-～、-〜”’・￥].*/;

				// ご意見・ご要望がある場合
				if(this.withdrawal_message){
					this.isInput = true;
					// console.log('a1:ご意見・ご要望がある場合');
					
					// ご意見・ご要望が改行とスペース以外の文字を含まない場合
					if( !regex.test(this.withdrawal_message) ){
						// 入力値は空判定
						this.isInput = false;
						// console.log('a2:ご意見・ご要望が改行とスペース以外の文字を含まない場合');
						// console.log('入力値: ' + this.isInput);

					// ご意見・ご要望が200文字以上の場合
					}else if(this.withdrawal_message.length > 200){
						// console.log('a3:ご意見・ご要望が200文字以上の場合');
						this.errors.withdrawal_message.push('ご意見・ご要望は、200文字以下で入力してください。');
					}

				// ご意見・ご要望がない場合
				}else{
					this.isInput = true;
					// console.log('a4:ご意見・ご要望がない場合');
				}
			},
			immediate: true,
		},

		form: {
			handler: function () {
				if(	(this.form.request && this.form.reason) && this.isInput && 
					!this.getErrorMessage(this.errors.request) && 
					!this.getErrorMessage(this.errors.reason) && 
					!this.getErrorMessage(this.errors.withdrawal_message) ){
					this.isDisabled = false;
				}else{
					this.isDisabled = true;
				}
			},
			deep: true,
			immediate: true,
		},
	},
}
</script>
