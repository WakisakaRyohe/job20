<script>
export default {
	// リアルタイムバリデーション
	watch: {
		email_new: {
			handler: function () {
				this.errors.email_new = [];

				// 新しいメールアドレスがある場合
				if(this.email_new) {
					// console.log('新しいメールアドレスがある場合');

					// 正規表現パターンを定義
					const regex = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/;

					// メールアドレスの形式が違う場合
					if(!regex.test(this.email_new)) {
						this.errors.email_new.push('メールアドレスの形式で入力してください。');
						this.errors.email_confirmation = [];
						// console.log('メールアドレスの形式が違う場合');

						// 「新しいメールアドレス」と「確認メールアドレス」が同じ（空でない）場合
						if(this.email_new === this.email_confirmation && this.email_confirmation){
							this.errors.email_confirmation = [];
							// console.log('「新しいメールアドレス」と「確認メールアドレス」が同じ（空でない）場合');
						}

					// 「現在のメールアドレス」と同じ場合
					}else if(this.email_old === this.email_new){
						this.errors.email_new.push('「現在のメールアドレス」と違うメールアドレスを入力してください。');
						// console.log('「現在のメールアドレス」と同じ場合');

						// 「新しいメールアドレス」と「確認メールアドレス」が同じ場合
						if(this.email_new === this.email_confirmation){
							this.errors.email_confirmation = [];
							// console.log(' 「新しいメールアドレス」と「確認メールアドレス」が同じ場合');
						}

					// 「新しいメールアドレス」が50文字以上の場合
					}else if(this.email_new.length > 50){
						this.errors.email_new.push('「新しいメールアドレス」は50文字以下で入力してください。');
						this.errors.email_confirmation = [];
						// console.log('「新しいメールアドレス」が50文字以上の場合');

					// 先に確認メールアドレスが入力され、後で新しいメールアドレスが入力された場合
					}else if(this.email_confirmation){
						this.errors.email_confirmation = [];
						// console.log('先に確認メールアドレスが入力され、後で新しいメールアドレスが入力された場合');

						// 「確認メールアドレス」と「新しいメールアドレス」が違う場合
						if(this.email_new !== this.email_confirmation){
							this.errors.email_confirmation.push('「新しいメールアドレス」と同じメールアドレスを入力してください。');
							// console.log('「確認メールアドレス」と「新しいメールアドレス」が違う場合');
						}
					}

				// 新しいメールアドレスがない場合
				}else{
					this.errors.email_new.push('新しいメールアドレスは入力必須です');
					// console.log('新しいメールアドレスがない場合');

					// 「確認メールアドレス」がない場合
					if(!this.email_confirmation) {
						this.errors.email_new = [];
						this.errors.email_confirmation = [];
						// console.log('「確認メールアドレス」がない場合');
					}
				}
			},
			immediate: true,
		},

		// 確認メールアドレス
		email_confirmation: {
			handler: function () {
				this.errors.email_confirmation = [];

				// 確認メールアドレスがある場合
				if(this.email_confirmation){
					// console.log('確認メールアドレスがある場合');
					
					// 「新しいメールアドレス」と違うメールアドレスの場合
					if(this.email_new !== this.email_confirmation && this.errors.email_new.length === 0) {
						this.errors.email_confirmation.push('「新しいメールアドレス」と同じメールアドレスを入力してください。');
						// console.log('「新しいメールアドレス」と違うメールアドレスの場合');
					}

				// 確認メールアドレスがない場合
				}else{
					this.errors.email_confirmation.push('確認メールアドレスは入力必須です');
					// console.log('確認用メールアドレスがない場合');
					
					// 「新しいメールアドレス」がない場合
					if(!this.email_new) {
						this.errors.email_new = [];
						this.errors.email_confirmation = [];
						// console.log('「新しいメールアドレス」がない場合');
					}
				}
			},
			immediate: true,
		},

		// フォームデータ(全項目のエラーメッセージが削除された後に判定)
		form: {
			handler: function () {
				if(this.email_new && this.email_confirmation && 
					! this.getErrorMessage(this.errors.email_new) && 
					! this.getErrorMessage(this.errors.email_confirmation) ){
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
