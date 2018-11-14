package contentbot

import ("bufio"
	"fmt"
	"github.com/jinzhu/configor"
	"github.com/PuerkitoBio/goquery"
	"net/http"
	"os"
	"strings")

type Crawler struct {
	Callback func(node *goquery.Selection) []string
	Config struct {
		Concurrency int
		Output string
	}
	Container string
	Fails string
	From string
	Tags *os.File
	To string
	Urls *os.File
}

func(crawler Crawler) Fail(fail string) Crawler {
	crawler.Fails = fail
	return crawler
}

func(crawler Crawler) Fetch() Crawler {
	queue := make(chan string)
	complete := make(chan bool)
	go func() {
		file, _ := os.Open(crawler.From)
		defer file.Close()
		scanner := bufio.NewScanner(file)
		for scanner.Scan() {
			queue <- scanner.Text()
		}
		close(queue)
	}()
	crawler.Tags, _ = os.Create(crawler.To)
	defer crawler.Tags.Close()
	crawler.Urls, _ = os.Create(crawler.Fails)
	defer crawler.Urls.Close()
	for i := 0; i < crawler.Config.Concurrency; i++ {
		go crawler.load(queue, complete)
	}
	for i := 0; i < crawler.Config.Concurrency; i++ {
		<-complete
	}
	return crawler
}

func(crawler Crawler) FromFile(file string) Crawler {
	crawler.From = file
	return crawler
}

func(crawler Crawler) Inject(config string) Crawler {
	configor.Load(&crawler.Config, config)
	return crawler
}

func(crawler Crawler) load(queue chan string, complete chan bool) {
	for url := range queue {
		response, err := http.Get(url)
		if nil == err {
			defer response.Body.Close()
			doc, _ := goquery.NewDocumentFromReader(response.Body)
			doc.Find(crawler.Container).Each(func(i int, node *goquery.Selection) {
				lines := crawler.Callback(node)
				for i := 0; i < len(lines); i++ {
					line := strings.TrimSpace(lines[i])
					if len(line) > 1 {
						crawler.Tags.Write([]byte(line + "\n"))
					}
				}
			})
		} else {
			fmt.Print("Failed on " + url + "\n")
			crawler.Urls.Write([]byte(url + "\n"))
		}

	}
	complete <- true
}

func(crawler Crawler) Select(container string) Crawler {
	crawler.Container = container
	return crawler
}

func(crawler Crawler) ToFile(file string) Crawler {
	crawler.To = file
	return crawler
}

func(crawler Crawler) Where(callback func(node *goquery.Selection) []string) Crawler {
	crawler.Callback = callback
	return crawler
}