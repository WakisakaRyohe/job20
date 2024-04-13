<script>
export default {
	// リアルタイムバリデーション
	watch: {
		email: {
			handler: function () {
				this.$store.commit('PasswordRemindSend/reset_errors_email', []);

				// 正規表現パターンを定義
				const regex = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/;

				if(this.email){
					// console.log('メールアドレスがある場合');

					// メールアドレスの形式が違う場合
					if(!regex.test(this.email)) {
						this.$store.commit('PasswordRemindSend/set_errors_email', 'メールアドレスの形式で入力してください。');
						// console.log('メールアドレスの形式が違う場合');

					// 50文字以上の場合
					}else if(this.email.length > 50){
						this.$store.commit('PasswordRemindSend/set_errors_email', '50文字以下で入力してください。');
						// console.log('50文字以上の場合');
					}

				// メールアドレスが空の場合
				}else{
					this.$store.commit('PasswordRemindSend/set_errors_email', 'メールアドレスは入力必須です。');
					// console.log('メールアドレスが空の場合');
				}

				// 送信ボタン有効化の判定
				if(this.email && !this.getErrorMessage(this.errors_email) ){
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
