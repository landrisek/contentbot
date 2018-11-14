export GOPATH=/home/user/contentbot
installPackage() {
	if [ ! -d $GOPATH/$@ ]; then
		go get $@	
	fi
}
installPackage github.com/PuerkitoBio/goquery
installPackage github.com/jinzhu/configor
installPackage golang.org/x/text/encoding/charmap