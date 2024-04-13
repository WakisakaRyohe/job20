<script>
import Certifications from '../mixin/Certifications.vue';
import Commitments from '../mixin/Commitments.vue';

export default {
	mixins: [ Certifications, Commitments ],
	methods:{
		// 画面幅を検知するイベント
		handleResizeModal: function() {
			// resizeのたびにこいつが発火するので、ここでやりたいことをやる
			const width = window.innerWidth;

			if(width > 768){
				this.$store.commit('EditSkill/setIsShowTable', true);
				this.$store.commit('Pagination/setIsShowTable', true);
			}else{
				this.$store.commit('EditSkill/setIsShowTable', false);
				this.$store.commit('Pagination/setIsShowTable', false);
			}
		},

		// モーダル表示
		open(type) {
			let items = [];
			let categoryNum = [];

			switch(type){
				case 'typeCertification':
					this.$store.commit('Modal/open', { modalName : "certification" });
					items = this.$store.state.EditSkill.skill.certifications;
					categoryNum = this.$store.state.EditSkill.categoryNum;
					break;
				case 'typeCommitment':
					this.$store.commit('Modal/open', { modalName : "commitment" });
					items = this.$store.state.Pagination.commitments;
					categoryNum = this.$store.state.Pagination.categoryNum;
					break;
			}

			// カテゴリ番号を初期化
			// switch(type){
			// 	case 'typeCertification':
			// 		this.$store.commit('EditSkill/setCategoryNum', 1);
			// 		break;
			// 	case 'typeCommitment':
			// 		this.$store.commit('Pagination/setCategoryNum', 1);
			// 		break;
			// }
			// console.log('選択中のカテゴリ番号を初期化：' + categoryNum);
        },

		// モーダル非表示
		close(type) {
			switch(type){
				case 'typeCertification':
					this.$store.commit('Modal/close', { modalName : "certification" });
					this.$store.commit('EditSkill/setSuggestList', []);
					this.$store.commit('EditSkill/setFreeword', '');
					break;
				case 'typeCommitment':
					this.$store.commit('Modal/close', { modalName : "commitment" });
					break;
			}
        },

		// タブ切り替え
		selectTab: function (type, num) {
			switch(type){
				case 'typeCertification':
					this.$store.commit('EditSkill/setActiveTab', num);
					break;
				case 'typeCommitment':
					this.$store.commit('Pagination/setActiveTab', num);
					break;
			}
        },

		// 選択中のカテゴリ変更（クリック）
		selectCategory: function (type, num) {
			switch(type){
				case 'typeCertification':
					this.$store.commit('EditSkill/setCategoryNum', num);
					break;
				case 'typeCommitment':
					this.$store.commit('Pagination/setCategoryNum', num);
					break;
			}
        },

		// チェックつけたアイテム名を確認
		checkItems: function (type,str) {
			switch(type){
				case 'typeCertification':
					return this.$store.state.EditSkill.skill.certifications.includes(str);
				case 'typeCommitment':
					return this.$store.state.Pagination.commitments.includes(str);
			}
        },

		// インクリメンタルサーチ
		incrementalSearch: function(type){
			// アイテムリスト・フリーワード・データを定義
			let retList = [];
			let freeword = '';

			switch(type){
				case 'typeCertification':
					freeword = this.$store.state.EditSkill.freeword;
					break;
			}

			// アイテム名が入力された場合
			if (freeword) {
				// console.log('入力されたフリーワード：' + freeword);

				let data = [];

				switch(type){
					case 'typeCertification':
						data = this.certificationsData;
						break;
				}

				data.forEach(category => {
					category.data.forEach(obj => {

						// アイテム名に入力された文字が含まれていた場合、リストに追加
						if (obj.name.indexOf(freeword) > -1) {
							retList.push(obj.name)
						}
					})
				});
			}
			// console.log('アイテム数：' + retList.length);

			// インクリメンタルサーチのリストに追加
			switch(type){
				case 'typeCertification':
					this.$store.commit('EditSkill/setSuggestList', retList);
					break;
			}
		},

		// フリーワード検索でアイテムが見つからない場合の、入力画面表示フラグをtrue
		noIndex(type){
			switch(type){
				case 'typeCertification':
					this.$store.commit('EditSkill/setNoIndex', true);
			}
		},
		// フリーワード検索画面に戻る（入力画面表示フラグをfalse）
		backSearchFreeword(type){
			switch(type){
				case 'typeCertification':
					this.$store.commit('EditSkill/setNoIndex', false);
			}
		},

		// フリーワード検索でアイテムが見つからない場合の、入力したフリーワードをアイテムの配列に追加
		addInputFreeword(type, str){
			if(str !== ''){
				switch(type){
					case 'typeCertification':
						if(!this.$store.state.EditSkill.skill.certifications.includes(str)){
							this.$store.commit('EditSkill/setInputFreewordToArray', str);
							this.$store.commit('EditSkill/setInputFreeword', '');
						}
						this.$store.commit('EditSkill/setNoIndex', false);
				}

				// チェックマークをつけるアイテムの配列を変更
				this.changeActiveCheckArray(type, str);
			}
		},

		// チェックつけたアイテムがカテゴリに含まれるか確認
		isCheckedCategory: function (type, num) {
			switch(type){
				case 'typeCertification':
					return this.$store.state.EditSkill.categories.includes(num);
				case 'typeCommitment':
					return this.$store.state.Pagination.categories.includes(num);
			}
        },

		// チェックマークをつけるか判定(iタグ)
		isActiveCheck: function (type, str) {
			switch(type){
				case 'typeCertification':
					return this.$store.state.EditSkill.activeCheckArray.includes(str);
				case 'typeCommitment':
					return this.$store.state.Pagination.activeCheckArray.includes(str);
			}
        },

		// チェックつけたアイテムが所属するカテゴリを確認
		checkItemsCategory: function (type, prop_category_id, item_name) {
			// console.log('チェックつけたアイテムが所属するカテゴリを確認ーーーーーーーーーーーーーーーーー');
			// console.log('タイプ：' + type);
			// console.log('引数のカテゴリID：' + prop_category_id);
			// console.log('アイテム名：' + item_name);

			let array = [];
			let categories = [];
			let data = [];

			// vuexからデータ取得
			switch(type){
				case 'typeCertification':
					array = this.$store.state.EditSkill.skill.certifications;
					categories = this.$store.state.EditSkill.categories;
					data = this.certificationsData;
					break;
				case 'typeCommitment':
					array = this.$store.state.Pagination.commitments;
					categories = this.$store.state.Pagination.categories;
					data = this.commitmentsData;
					break;
			}
			// console.log('アイテムの配列：' + array);
			// console.log('カテゴリーの配列：' + categories);

			// カテゴリIDを定義
			let category_id = prop_category_id;

			// 引数にアイテム名がある場合、カテゴリIDを取得
			if(item_name){
				data.some(function(category){

					// アイテム名を含むアイテムオブジェクトを取得
					const obj = category.data.find((item) => item.name === item_name);

					// アイテムオブジェクトからカテゴリIDを取得
					if(obj){
						category_id = obj.category_id;
						return true;
					}
				});
			}
			// console.log('カテゴリID：' + category_id);

			if(category_id){
				// 選択中のカテゴリに属するアイテムデータ取得
				const categoryObj = data.find((category) => category.id === category_id);
				const dataArray = categoryObj.data;

				// 選択中のカテゴリ配列に値がない場合、追加
				if(!categories.includes(category_id)){
					categories.push(category_id);
					// console.log('選択中のカテゴリ配列に値がない場合、追加：' + categories);

				// 選択中のカテゴリ配列に値がある場合
				}else{
					// 選択中のアイテム配列に値がある場合
					if(array.length > 0){

						// 選択中のカテゴリ配列の要素削除の判定フラグ
						let deleteFlg = false;

						// 選択中のアイテムIDをループ
						for(let i = 0 ; i < array.length ; ++i){

							// 選択中のカテゴリに属するアイテムデータ
							const dataObj = dataArray.find((data) => data.name === array[i]);

							if(dataObj !== undefined){
								// console.log('選択中のカテゴリにチェックしたアイテムデータあり。ループ脱出');
								deleteFlg = false;
								break;
							}else{
								deleteFlg = true;
							}
						}
						// console.log('選択中のカテゴリ配列の要素削除の判定フラグ' + deleteFlg);

						if(deleteFlg){
							// 選択中のカテゴリ配列から値を削除し、新たな配列を作成
							let newArray = categories.filter(function(categoryNum) {
								return categoryNum !== category_id;
							});

							switch(type){
								case 'typeCertification':
									this.$store.commit('EditSkill/setCategories', newArray);
									break;
								case 'typeCommitment':
									this.$store.commit('Pagination/setCategories', newArray);
									break;
							}
							// console.log('選択中のカテゴリ配列から値を削除し、新たな配列を作成');
							console.log(categories);
						}

					// 選択中のアイテム配列に値がない場合
					}else{
						switch(type){
							case 'typeCertification':
								this.$store.commit('EditSkill/setCategories', []);
								break;
							case 'typeCommitment':
								this.$store.commit('Pagination/setCategories', []);
								break;
						}
						// console.log('選択中のアイテム配列に値なし。カテゴリ配列を初期化：' + categories);
					}
				}
			}
		},

		// チェックマークをつけるアイテムの配列を変更
		changeActiveCheckArray: function (type, str) {
			// console.log('チェックマークをつけるアイテムの配列を変更ーーーーーーーーーーーーーーーーー');
			// console.log('タイプ：' + type);
			// console.log('アイテム名：' + str);

			let activeCheckArray = [];

			switch(type){
				case 'typeCertification':
					activeCheckArray = this.$store.state.EditSkill.activeCheckArray;
					break;
				case 'typeCommitment':
					activeCheckArray = this.$store.state.Pagination.activeCheckArray;
					break;
			}

			// チェックマークをつけるアイテムの配列に値がある場合
			if(activeCheckArray.includes(str)){
				// チェックマークをつけるアイテムの配列から、値を削除（配列を新たに作成）
				let newArray = activeCheckArray.filter(function(name) {
					return name !== str;
				});

				switch(type){
					case 'typeCertification':
						this.$store.commit('EditSkill/setActiveCheckArray', newArray);
						break;
					case 'typeCommitment':
						this.$store.commit('Pagination/setActiveCheckArray', newArray);
						break;
				}
				// console.log('チェックマークをつけるアイテムの配列から、値を削除（配列を新たに作成）');

			// チェックマークをつけるアイテムの配列に値がない場合
			}else{
				switch(type){
					case 'typeCertification':
						this.$store.commit('EditSkill/setActiveCheckName', str);
						break;
					case 'typeCommitment':
						this.$store.commit('Pagination/setActiveCheckName', str);
						break;
				}
				// console.log('チェックマークをつけるアイテムの配列に値がないため、追加');
			}

			switch(type){
				case 'typeCertification':
					// console.log('チェックマークをつけるアイテムの配列：' + this.$store.state.EditSkill.activeCheckArray);
					break;
				case 'typeCommitment':
					// console.log('チェックマークをつけるアイテムの配列：' + this.$store.state.Pagination.activeCheckArray);
					break;
			}
		},

		// 選択したアイテムを配列から削除
		deleteCheck(type, str){
			// console.log('選択したアイテムを配列から削除ーーーーーーーーーーーーーーーーー');
			// console.log('タイプ：' + type);
			// console.log('削除するアイテム名：' + str);

			let array = [];

			switch(type){
				case 'typeCertification':
					array = this.$store.state.EditSkill.skill.certifications;
					break;
				case 'typeCommitment':
					array = this.$store.state.Pagination.commitments;
					break;
			}
			// console.log('アイテムの配列：' + array);

			if(array.includes(str)){
				// チェックマークをつけるアイテムの配列から、値を削除（配列を新たに作成）
				let newArray = array.filter(function(name) {
					return name !== str;
				});

				switch(type){
					case 'typeCertification':
						this.$store.commit('EditSkill/set_certifications_all', newArray);
						break;
					case 'typeCommitment':
						this.$store.commit('Pagination/setCommitmentsAll', newArray);
						break;
				}
			}

			switch(type){
				case 'typeCertification':
					// console.log('削除後のアイテムの配列：' + this.$store.state.EditSkill.skill.certifications);
					break;
				case 'typeCommitment':
					// console.log('削除後のアイテムの配列：' + this.$store.state.Pagination.commitments);
					break;
			}
		},
	},

	computed:{
		// 入力データをバインディング
		// 資格
		certifications: {
			get() {
				return this.$store.state.EditSkill.skill.certifications;
			},
			set(str) {
				this.$store.commit('EditSkill/set_certifications', str);
			}
		},
		freeword: {
			get() {
				return this.$store.state.EditSkill.freeword;
			},
			set(str) {
				this.$store.commit('EditSkill/setFreeword', str);
			}
		},
		inputFreeword: {
			get() {
				return this.$store.state.EditSkill.inputFreeword;
			},
			set(str) {
				this.$store.commit('EditSkill/setInputFreeword', str);
			}
		},
		// こだわり条件
		commitments: {
			get() {
				return this.$store.state.Pagination.commitments;
			},
			set(data) {
				this.$store.commit('Pagination/setCommitments', data);
			}
		},

		isActiveTab: function () {
			// 関数の返り値に別の関数を定義し、別の関数内で引数を受け取る
			return function(type) {
				switch(type){
					case 'typeCertification':
						return this.$store.state.EditSkill.isActiveTab;
					case 'typeCommitment':
						return this.$store.state.Pagination.isActiveTab;
				}
			}
		},

		isShowModal: function () {
			return function(type) {
				switch(type){
					case 'typeCertification':
						return this.$store.state.Modal.modal.certification.isShow;
					case 'typeCommitment':
						return this.$store.state.Modal.modal.commitment.isShow;
				}
			}
		},

		categoryNum: function() {
			return function(type) {
				switch(type){
					case 'typeCertification':
						return this.$store.state.EditSkill.categoryNum;
					case 'typeCommitment':
						return this.$store.state.Pagination.categoryNum;
				}
			}
		},

		suggestList: function() {
			return function(type) {
				switch(type){
					case 'typeCertification':
						return this.$store.state.EditSkill.suggestList;
				}
			}
		},

		isNoIndex: function() {
			return function(type) {
				switch(type){
					case 'typeCertification':
						return this.$store.state.EditSkill.isNoIndex;
				}
			}
		},

		isShowTable: function() {
			return function(type) {
				switch(type){
					case 'typeCertification':
						return this.$store.state.EditSkill.isShowTable;
					case 'typeCommitment':
						return this.$store.state.Pagination.isShowTable;
				}
			}
		},
	},

	mounted(){
		this.handleResizeModal();
		// 画面幅を取得するイベントを設定
		window.addEventListener('resize', this.handleResizeModal);
	},

	beforeDestroy: function () {
		window.removeEventListener('resize', this.handleResizeModal)
	},
}
</script>
