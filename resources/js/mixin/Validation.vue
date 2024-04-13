<script>
export default {
	data(){
		return {
			isDisabled: true,
			objEmptyFlg: true,
			// ボタンクリック判定
			isClicked: false,
		}
	},

	methods: {
		getProfileArray(){
			return Object.entries(this.profile);
		},
		getErrorsArray(){
			return Object.entries(this.errors);
		},
		// 入力フォームの文字数を取得
		inputLength: function(str){
			if(str){
				return str.length;
			}else{
				return 0;
			}
		},
		// 入力フォームで文字数が制限以上なら、入力した文字数を赤色に変更
		inputLengthColor: function(length, num){
			if(this.inputLength(length) >= num){
				return 'rgb(255,0,0)';
			}
		},
	},

	computed: {
		// エラーメッセージを返却
		getErrorMessage: function() {
			return function(errorArray) {
				if(errorArray){
					return errorArray[0];
				}
			}
		},

		// バリデーション失敗時、フォームの背景色を薄い赤色に変更
		inputColor: function() {
			return function(errorText) {
				if(errorText){
					return 'rgb(255, 190, 190)';
				}
			}
		},

		// エラーメッセージの存在チェック
		hasErrors(){
			let hasErrors = false;

			const errorsArray = Object.values(this.errors);

			errorsArray.forEach(error => {
				if(error.length !== 0){
					hasErrors = true;
				}
			});

			return hasErrors;
		},
	},
}
</script>

<style>
.disabled{
    background: #c5c5c5;
}
.disabled:hover{
	cursor: not-allowed;
}
</style>