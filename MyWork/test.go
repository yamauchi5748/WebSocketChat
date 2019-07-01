package test

type Server struct{}

func (server) ServerHTTP(res http.ResponseWriter, request *http.Request) {
	// フォームの入力値
	left := request.FormValue("left")
	right := request.FormValue("right")
	op := request.FormValue("op")

	// 文字列を整数に変換
	leftInt := &big.Int{}
}