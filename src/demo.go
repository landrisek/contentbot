package main

import ("contentbot"
	"github.com/PuerkitoBio/goquery"
	"regexp")

var crawler = contentbot.Crawler{}.Inject("config.yml")

func main() {
	idnes()
}

func idnes() {
	callback := func(node *goquery.Selection) []string {
		return regexp.MustCompile("\\.|\\!|\\?").Split(node.Find("p").Text(), -1)
	}
	crawler.FromFile("../data/input.csv").Select("#disc-list .user-text").Where(callback).ToFile(
		"../data/output.csv").Fetch()
}

func idnesLinks() {
	callback := func(node *goquery.Selection) []string {
		tag, _ := node.Find("a").Attr("href")
		return []string{tag}
	}
	crawler.FromFile("../data/input.csv").Select("table.moot-table tbody tr").Where(callback).ToFile(
		"../data/output.csv").Fetch()
}