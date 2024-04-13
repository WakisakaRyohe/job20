import axios from 'axios';
import router from './router';

// エラーハンドリング
const customAxios = axios.interceptors.response.use(
    (response) => response, // 成功時の処理(responseを返すだけ)
    (error) => {            // 失敗時の処理
            // エラーコード別に共通処理
            switch (error.response.status) {

            case 400: // Bad Request
                router.push({ name: 'error' });
                // location.href = '/job20/error';
                throw error; // 返ってきたエラーメッセージを各ページ内で表示したいのでthrowする

            case 401: // Unauthorized
                // console.log('ページの閲覧にはログインが必要です');

                // laravelではログアウト状態でも、vueではログイン状態の場合
                if(localStorage.getItem('isLogin') === 'true'){
					// ローカルストレージの全ての値を削除
					localStorage.clear();
                }

                router.push({ name: 'unauthorized'});
                // location.href = '/job20/unauthorized';
                return Promise.reject(error, error.response.data); // errorインスタンスを返す

            case 403: // Forbidden
                // console.log('ページの閲覧権限がありません');
                router.push({ name: 'forbidden'});
                // location.href = '/job20/forbidden';
                return Promise.reject(error, error.response.data);

            case 404: // Not Found
                // console.log('お探しのページが見つかりません。customAxios.js');
                router.push({ name: 'notfound'});
                // location.href = '/job20/notfound';
                return Promise.reject(error, error.response.data);

            case 419:
                // console.log('トークン認証エラーです。');
                router.push({ name: 'error' });
                // location.href = '/job20/error';
                return Promise.reject(error, error.response.data);

            case 422: // Unprocessable Entity
                // console.log('バリデーションに失敗しました。');
                return Promise.reject(error, error.response.data);

            case 500: // Internal Server Error
                // console.log('サーバー内部エラーが発生しました');
                router.push({ name: 'error' });
                // location.href = '/job20/error';
                return Promise.reject(error, error.response.data);

            default: // 上記以外のエラーコードに対する共通処理
                // console.log('予期しないエラーが発生しました');
                router.push({ name: 'error' });
                // location.href = '/job20/error';
                return Promise.reject(error, error.response.data);
        }
    }
);

export default customAxios
