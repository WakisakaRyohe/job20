<script>
export default {
	// リアルタイムバリデーション
	watch: {
		// errors: {
		// 	handler: function () {
		// 		if(this.auth_key && !this.getErrorMessage(this.errors.auth_key) ){
		// 			this.isDisabled = false;
		// 		}else{
		// 			this.isDisabled = true;
		// 		}
		// 	},
		// 	deep: true,
		// },
		auth_key: function(){
			this.errors.auth_key = [];

			// 認証キーが違う場合
			if(this.auth_key && this.auth_key !== this.session_auth_key){
				this.errors.auth_key.push('認証キーが違います。');
				// console.log('認証キーが違う場合');

			// 認証キーが空の場合
			}else if(!this.auth_key){
				this.errors.auth_key.push('認証キーは入力必須です。');
				// console.log('認証キーが空の場合');
			}

			// 再発行ボタン活性化の判定
			if(this.auth_key && !this.getErrorMessage(this.errors.auth_key) ){
				this.isDisabled = false;
			}else{
				this.isDisabled = true;
			}
		},
	},
}
</script>
