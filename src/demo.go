package main

import ("contentbot"
	"github.com/PuerkitoBio/goquery"
	"golang.org/x/text/encoding/charmap"
	"regexp")

var crawler = contentbot.Crawler{}.Inject("config.yml")

func main() {
	parse()
}

func idnes() {
	callback := func(node *goquery.Selection) []string {
		tag, _ := node.Attr("href")
		return []string{"https://ekonomika.idnes.cz/" + tag}
	}
	crawler.FromFile("../data/input.csv").Select("a#moot-linkin").Where(callback).ToFile(
		"../data/output.csv").Fail("../data/fails.csv").Fetch()
}

func parse() {
	callback := func(node *goquery.Selection) []string {
		row := decodeWindows1250([]byte(node.Find("p").Text()))
		return regexp.MustCompile("\\.|\\!|\\?").Split(row, -1)
	}
	crawler.FromFile("../data/input.csv").Select("#disc-list .user-text").Where(callback).ToFile(
		"../data/output.csv").Fail("../data/fails.csv").Fetch()
}

func decodeWindows1250(encoded []byte) string {
	decoded := charmap.Windows1250.NewDecoder()
	out, _ := decoded.Bytes(encoded)
	return string(out)
}

func encodeWindows1250(inp string) string {
	enc := charmap.Windows1250.NewEncoder()
	out, _ := enc.String(inp)
	return out
}