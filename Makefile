TEX_MAIN = report.tex

all: report

report: $(TEX_MAIN)
	latexmk -pdf -shell-escape $(TEX_MAIN) 

clean:
	latexmk -C $(TEX_MAIN)
	rm -f *.bbl 