<script>
export default {
	data(){
		return {
			isClicked: false,
			isLoading: true,
		}
	},

	methods: {
		// モーダル表示・非表示
		openApplyModal(propNum, propID){
			this.$store.commit('Modal/open', { modalName: "apply", num: propNum, id: propID});
		},
		closeApplyModal() {
			this.$store.commit('Modal/close', { modalName : "apply" });
		},

		// 求人応募
		applyJob: async function (id){
			// 処理中の画面表示
			this.$store.commit('ApplyJob/setIsLoading', true);
			// ボタン連打を無効化
			this.isClicked = true;
			// axiosで使うthisを格納
			const self = this;

			// 非同期通信
			await axios.post('/job20/web/job/' + id)
			.then(response => {
				if(response.data.successFlg){
					// 遷移先ページでフラッシュメッセージ表示
					self.$store.commit('Modal/setFlash', true);
					self.$store.commit('Modal/setMessage', '求人に応募しました。<br>会社とメッセージで<br class="u-spShow">やり取りしましょう！');
					self.$store.commit('FormChanged/set_formChanged', false);

					// 連絡掲示板ページに遷移
					self.$router.push({
						name: 'bord', 
						id: response.data.bordId, // propsとして渡すid
						params: {
							id: response.data.bordId // クエリパラメータとして渡すid
						}
					})
				}else{
					self.$router.push({ name: 'error'}, () => {});	
				}
			}).catch(error => console.log(error));

			// ボタンを初期状態に戻す
			this.isClicked = false;
			// 処理中の画面を非表示
			this.$store.commit('ApplyJob/setIsLoading', false);
		},

		// 気になる求人を削除
		removeBookmarkJob: async function (index, id) {
			// ボタン連打を無効化
			this.isClicked = true;
			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/web/bookmark/' + id + '/delete')
			.then(response => {
				if(response.data.successFlg){
					// 気になる求人一覧ページの場合、削除後の求人リストを返却
					if(index >= 0){
						// アラートを表示する配列から削除
						let newArray = self.activeAlertArray.filter(function(number) {
							return number !== id;
						});
						self.activeAlertArray = newArray;

						setTimeout(()=>{
							// 画面から求人を削除(第1引数で指定した要素から、第2引数で指定した数の分の要素を取り除く)
							self.jobs.splice(index, 1);
						}, 0);
					}
				}else{
					self.$router.push({ name: 'error'}, () => {});	
				}
			}).catch(error => console.log(error));

			// ボタンを初期状態に戻す
			this.isClicked = false;
		},
	},

	computed:{
		// モーダル表示判定フラグ
		isShowApply: function () {
			return this.$store.state.Modal.modal.apply.isShow;
		},
		isShowDereteBookmarkJobsModal: function () {
			return this.$store.state.Modal.modal.BookmarkJobs.isShow;
		},
		// モーダル情報を返却
		applyID: function () {
			return this.$store.state.Modal.modal.apply.id;
		},
	},
}
</script>
