#!/bin/bash
set -x
cd ~/dev/poker
bak -n99 -s $* utl/*
bak -n99 -s $* utl/data/*
bak -n99 -s $* utl/work/*
bak -n99 -s $* html/*
bak -n99 -s $* html/class/*
bak -n99 -s $* html/func/*
bak -n99 -s $* html/img/*
bak -n99 -s $* html/includes/*
bak -n99 -s $* html/modules/*
bak -n99 -s $* html/modules/game/*
bak -n99 -s $* html/modules/player/*
bak -n99 -s $* html/modules/seat/*
bak -n99 -s $* html/style/*


