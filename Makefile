#
# Makefile for building the music-composition WordPress plugin
#

VERSION = 1.0.0

SOURCE_FILES := $(wildcard source/*)

.PHONY: clean test all

all: music-composition-$(VERSION).zip

test:
	echo $(SOURCE_FILES)

clean:
	rm -rf music-composition
	rm -f music-composition-$(VERSION).zip

music-composition-$(VERSION).zip: $(SOURCE_FILES)
	rm -rf music-composition; \
	mkdir music-composition; \
	cp -r source/* music-composition; \
	zip -r music-composition-$(VERSION).zip music-composition
