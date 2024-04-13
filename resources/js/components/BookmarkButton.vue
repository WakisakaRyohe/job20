<template>
	<button v-if="text" @click=bookmark class="c-btn--bookmark" 
		:class='{bookmarkDelete:!isActive}' :value="id" :disabled="isClicked">
		<i class="fa-solid fa-star"></i>{{ text }}
	</button>
</template>

<script>
export default {
	props: [
		'id',
	],
	data(){
		return {
			isActive: true,
			textFlg: true,
			text: '',
			bookmarkID: 0,
			isClicked: false,
		}
	},

	methods:{
		// 気になる確認
		checkBookmark: function (){
			// axiosで使うthisを格納
			const self = this;

			axios.get('/job20/web/bookmark/' + this.id)
			.then(response =>{
				if(response.data.successFlg){
					if(response.data.bookmarkJobs){
						self.isActive = false;
						self.textFlg = false;
						self.text = '気になる解除';
					}else{
						self.isActive = true;
						self.textFlg = true;
						self.text = '気になる';
					}
				}else{
					self.$router.push({ name: 'error'}, () => {});	
				}
			}).catch(error => console.log(error));
		},

		// 気になる登録・削除の処理
		bookmark: async function(event){
			// ボタン連打を無効化
			this.isClicked = true;

			// 気になる一覧の中で、クリックされた求人のIDを取得
			this.bookmarkID = event.target.value;

			// axiosで使うthisを格納
			const self = this;

			// 中間テーブルでレコードの追加
			if(this.text === '気になる'){
				await axios.post('/job20/web/bookmark/' + this.bookmarkID)
				.then(response =>{
					if(!response.data.successFlg){
						self.$router.push({ name: 'error'}, () => {});	
					}
				}).catch(error => console.log(error));

			// 中間テーブルでレコードの削除
			}else{
				await axios.post('/job20/web/bookmark/' + this.bookmarkID + '/delete')
				.then(response =>{
					if(!response.data.successFlg){
						self.$router.push({ name: 'error'}, () => {});	
					}
				}).catch(error => console.log(error));
			}

			// dataを反転して表示切り替え
			this.isActive = !this.isActive;
			this.textFlg = !this.textFlg;
			this.textFlg == true ? this.text = '気になる' : this.text = '気になる解除';

			// ボタンを初期状態に戻す
			this.isClicked = false;
		}
	},

	created(){
		this.checkBookmark();
	},

	mounted(){
	},
}
</script>

<style>
.bookmarkDelete {
    background: #c5c5c5;
	box-shadow: none;
}
</style>
