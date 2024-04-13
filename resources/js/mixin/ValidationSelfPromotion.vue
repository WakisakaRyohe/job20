<script>
export default {
	data(){
		return {
			isInput: false,
		}
	},

	// リアルタイムバリデーション
	watch: {
		// 自己PR
		self_promotion: {
			handler: function () {
				// テキストエリアの高さ変更
				this.resizeSelfPromotionArea();
				// エラー配列を空にする
				this.errors.self_promotion = [];
				// 正規表現パターンを定義(改行とスペース以外の文字を含む)
				const regex = /.*[ぁ-んァ-ヶー一-龯0-9０-９a-zA-Z!?_+*'"`#$%&\-^\\@;:,./=~|[\](){}<>¥！-／：-＠［-｀｛-～、-〜”’・￥].*/;

				// 自己PRがある場合
				if(this.self_promotion){
					this.isInput = true;
					// console.log('a1:自己PRがある場合');
					
					// 自己PRと志望動機が改行とスペース以外の文字を含まない場合
					if(!regex.test( this.self_promotion) && !regex.test(this.motivation) ){
						// 入力値は空判定
						this.isInput = false;
						// console.log('a2:自己PRと志望動機が改行とスペース以外の文字を含まない場合');
						// console.log('入力値: ' + this.isInput);

					// 自己PRが800文字以上の場合
					}else if(this.self_promotion.length > 800){
						// console.log('a3:自己PRが800文字以上の場合');
						this.errors.self_promotion.push('自己PRは、800文字以下で入力してください。');
					}

				// 自己PRがない場合
				}else{
					// console.log('a4:自己PRがない場合');

					// 志望動機が改行とスペース以外の文字を含まない場合
					if(!regex.test(this.motivation) ){
						// 入力値は空判定
						this.isInput = false;
						// console.log('a5:志望動機が改行とスペース以外の文字を含まない場合');
						// console.log('入力値: ' + this.isInput);
					}
				}
			},
			immediate: true,
		},

		// 志望動機
		motivation: {
			handler: function () {
				// テキストエリアの高さ変更
				this.resizeMotivationArea();
				// エラー配列を空にする
				this.errors.motivation = [];
				// 正規表現パターンを定義(改行とスペース以外の文字を含む)
				const regex = /.*[ぁ-んァ-ヶー一-龯0-9０-９a-zA-Z!?_+*'"`#$%&\-^\\@;:,./=~|[\](){}<>¥！-／：-＠［-｀｛-～、-〜”’・￥].*/;

				// 志望動機がある場合
				if(this.motivation){
					this.isInput = true;
					// console.log('b1:志望動機がある場合');

					// 自己PRと志望動機が改行とスペース以外の文字を含まない場合
					if(!regex.test( this.self_promotion) && !regex.test(this.motivation) ){
						// 入力値は空判定
						this.isInput = false;
						// console.log('b2:自己PRと志望動機が改行とスペース以外の文字を含まない場合');
						// console.log('入力値: ' + this.isInput);

					// 志望動機が800文字以上の場合
					}else if(this.motivation.length > 800){
						// console.log('b3:志望動機が800文字以上の場合');
						this.errors.motivation.push('志望動機は、800文字以下で入力してください。');
					}

				// 志望動機がない場合
				}else{
					// console.log('b4:志望動機がない場合');

					// 自己PRが改行とスペース以外の文字を含まない場合
					if(!regex.test( this.self_promotion) ){
						// 入力値は空判定
						this.isInput = false;
						// console.log('b5:自己PRとが改行とスペース以外の文字を含まない場合');
						// console.log('入力値: ' + this.isInput);
					}
				}
			},
			immediate: true,
		},

		// 自己PRオブジェクト（最後じゃないと上手く作動しない）
		self_promotion_data: {
			handler: function () {
				// １つでも入力しないと更新ボタン押せない
				if(	(this.self_promotion || this.motivation) && this.isInput && 
				   !this.getErrorMessage(this.errors.self_promotion) && !this.getErrorMessage(this.errors.motivation) ){
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
