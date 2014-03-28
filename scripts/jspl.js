/*
 * Terminal
 */

var Terminal = function (name) {
    this.name = name;
    this.buffer = new Array();
    this.buffer[0] = "";
    this.cursor = 0;
    this.height = 25;
    this.width = 80;
}

Terminal.prototype.redraw = function () {
    $("#" + this.name).text("");
    var topLine = (this.buffer.length >= this.height) ?
        (this.buffer.length - this.height) : 0;
    for (var i = topLine; i < this.buffer.length; i++) {
        l = this.buffer[i];
        if (i == this.buffer.length - 1) {
            var start = l.substr(0, this.cursor);
            var end = l.substr(this.cursor + 1);
            cursorChar = (end == '' ? ' ' : l[this.cursor + 1]);
            cursorChar = htmlEncode(cursorChar);
            cursorChar = '<span class="cursorChar">' + cursorChar + '</span>';
            l = htmlEncode(start) + cursorChar + htmlEncode(end);
        } else {
            l = htmlEncode(l);
        }
        $("#" + this.name).append(l);
        $("#" + this.name).append("<br />");
    }

    if (this.buffer.length < this.height) {
        for (var i = 0; i < this.height - this.buffer.length; i++) {
            $("#" + this.name).append("<br />");
        }
    }
}

Terminal.prototype.write = function (s) {
    for (var i = 0; i < s.length; i++) {
        this.writeChar(s[i]);
    }
}

Terminal.prototype.writeChar = function (c) {
    l = this.buffer.length - 1;
    if (c == '\n') {
        this.buffer[l + 1] = "";
        this.cursor = 0;
        return;
    }
    if (c == '\r') {
        console.log("caught r here");
        this.cursor = 0;
        this.buffer[l] = "";
        return;
    }
    if (this.buffer[l].length >= this.width) {
        this.buffer[l + 1] = "";
        this.cursor = 0;
        l++;
    }
    //this.buffer[l] += c;
    // split string at cursor, add new char and put the line back together
    //console.log("----");
    //console.log("current line: [" + this.buffer[l] + "]");
    var start = this.buffer[l].substr(0, this.cursor);
    var end = this.buffer[l].substr(this.cursor);
    //console.log("start: [" + start + "]");
    //console.log("end: [" + end + "]");
    //console.log("c: [" + c + "]");
    this.buffer[l] = start + c + end;
    this.cursor++;
}

Terminal.prototype.deleteChar = function () {
    l = this.buffer.length - 1;
    var start = this.buffer[l].substr(0, this.cursor);
    var end = this.buffer[l].substr(this.cursor + 1);
    this.buffer[this.buffer.length - 1] = start + end;
}

/*
 * TTYLog
 */

var TTYLog = function (logfile) {
    this.data = new BinFileReader(logfile);

    // we'll use these to avoid going EOF
    this.readcount = 0;
    this.filesize = this.data.getFileSize();
}

TTYLog.prototype.read = function () {
    var op = this.data.readNumber(4);
    var tty = this.data.readNumber(4);
    var length = this.data.readNumber(4);
    var dir = this.data.readNumber(4);
    var sec = this.data.readNumber(4);
    var usec = this.data.readNumber(4);
    this.readcount += 24
    var stamp = parseFloat(sec + "." + usec);
    return {
        op: op,
        tty: tty,
        length: length,
        dir: dir,
        sec: sec,
        usec: usec,
        stamp: stamp,
    };
}

TTYLog.prototype.readString = function (length) {
    this.readcount += length;
    return this.data.readString(length);
}

TTYLog.OP_OPEN = 1
TTYLog.OP_CLOSE = 2
TTYLog.OP_WRITE = 3
TTYLog.OP_EXEC = 4
TTYLog.TYPE_INPUT = 1
TTYLog.TYPE_OUTPUT = 2
TTYLog.TYPE_INTERACT = 3

/*
 * stuff
 */

function tick() {
    tick.counter++;
    if (ttylog.readcount >= ttylog.filesize) return -1; // EOF
    packet = ttylog.read();
    if (packet.length == 0) {
        return packet.stamp;
    }

    var write = function (s) {
        if (packet.dir != TTYLog.TYPE_OUTPUT) return;
        //terminal.write(htmlEncode(s));
        terminal.write(s);
    };

    var i = 0;
    while (i < packet.length) {
        i++;
        c = ttylog.readString(1);
        var num = c.charCodeAt(0);
        if (num == 27) {
            var escdata = '';
            escdata = ttylog.readString(1);
            i++;
            if (escdata == 'c') {
                write("^C\n");
                break;
            }
            if (escdata != "[") break;
            while (1) {
                tmp = ttylog.readString(1);
                i++;
                escdata += tmp;
                if (tmp.charCodeAt(0) >= 64 &&
                    tmp.charCodeAt(0) <= 126) {
                    //console.log("escdata:" + escdata);
                    if (escdata == "[1D") {
                        terminal.cursor--;
                    } else if (escdata == "[1P") {
                        terminal.deleteChar();
                    } else {
                        console.log("Unhandled escdata: " + escdata);
                    }
                    break;
                }
            }
        } else {
            write(c);
        }
    }

    terminal.redraw();

    return packet.stamp;
}
tick.counter = 0;

function autoTick() {
    var stamp = tick();

    if (stamp == -1) {
        console.log("end of file!");
        terminal.write("\n\n*** End of log! ***\n");
        terminal.redraw();
        return;
    }

    var sleep = 0;
    if (autoTick.prevStamp > 0) {
        sleep = stamp - autoTick.prevStamp;
        if (sleep > 3) {
            sleep = 3.0;
        }
    }
    sleep = sleep * 1000;
    autoTick.prevStamp = stamp;

    //if (tick.counter < 80) sleep = 0;
    //if (tick.counter > 95) return;

    setTimeout(autoTick, sleep);
}
autoTick.prevStamp = 0;

function htmlEncode(value) {
    value = (value ? jQuery('<div />').text(value).html() : '');
    value = value.replace(/ /g, "&nbsp;");
    return value;
}

function blinkCursor() {
    if ($(".cursorChar").css("text-decoration") != "underline") {
        $(".cursorChar").css("text-decoration", "underline");
    } else {
        $(".cursorChar").css("text-decoration", "none");
    }
    setTimeout(blinkCursor, 500);
}

jQuery(document).ready(function ($) {
    terminal = new Terminal("playlog");

    var fileName = $(document).getUrlParam("f");

    blinkCursor();
    if (fileName !== null) {
        $("#playlog").css('display', 'block');
        $("#description").html("Playing <b>" + fileName + "</b>");
        ttylog = new TTYLog(fileName);
        autoTick();
    } else {
        $("#description").html("No input file specified!");
    }

});

// vim: set sw=4 et:
