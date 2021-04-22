!function (t) {
    "function" == typeof define && define.amd ? define(["jquery"], t) : t(jQuery)
}(function (t) {
    var e = function (e, n) {
        var n = n || t(e.imageBox),
            i = {
                state: {},
                ratio: 1,
                options: e,
                imageBox: n,
                thumbBox: n.find(e.thumbBox),
                spinner: n.find(e.spinner),
                image: new Image,
                getDataURL: function () {
                    var t = this.thumbBox.width(),
                        e = this.thumbBox.height(),
                        i = document.createElement("canvas"),
                        a = n.css("background-position").split(" "),
                        o = n.css("background-size").split(" "),
                        s = parseInt(a[0]) - n.width() / 2 + t / 2,
                        r = parseInt(a[1]) - n.height() / 2 + e / 2,
                        u = parseInt(o[0]),
                        g = parseInt(o[1]),
                        c = parseInt(this.image.height),
                        m = parseInt(this.image.width);
                    i.width = t, i.height = e;
                    var p = i.getContext("2d");

                    var ratio = 0;
                    if (m > c) {
                        ratio = m / t;
                    } else {
                        ratio = c / e;
                    }

                    if (ratio > 5) {
                        ratio = 5;
                    }

                    $(i).attr('width', t * ratio);
                    $(i).attr('height', e * ratio);

                    s *= ratio;
                    r *= ratio;
                    u *= ratio;
                    g *= ratio;

                    p.drawImage(this.image, 0, 0, m, c, s, r, u, g);
                    var d = i.toDataURL("image/png");
                    return d
                },
                getBlob: function () {
                    for (var t = this.getDataURL(), e = t.replace("data:image/png;base64,", ""), n = atob(e), i = [], a = 0; a < n.length; a++)
                        i.push(n.charCodeAt(a));
                    return new Blob([new Uint8Array(i)], {
                        type: "image/png"
                    })
                },
                zoomIn: function () {
                    this.ratio *= 1.1, a()
                },
                zoomOut: function () {
                    this.ratio *= .9, a()
                },
                updateImg: function () {
                    a()
                }
            },
            a = function () {
                var thumb = document.querySelector('.thumbBox');
                var thumb_width = parseInt($(thumb).css('width'));
                var zoom = 1;
                if (i.image.width >= i.image.height) {
                    zoom = thumb_width / i.image.width;
                } else {
                    zoom = thumb_width / i.image.height;
                }

                var t = parseInt(i.image.width) * i.ratio * zoom,
                    e = parseInt(i.image.height) * i.ratio * zoom,
                    a = (n.width() - t) / 2,
                    o = (n.height() - e) / 2;
                n.css({
                    "background-image": "url(" + i.image.src + ")",
                    "background-size": t + "px " + e + "px",
                    "background-position": a + "px " + o + "px",
                    "background-repeat": "no-repeat"
                })
            },
            o = function (t) {
                t.stopImmediatePropagation(), i.state.dragable = true, i.state.mouseX = t.clientX, i.state.mouseY = t.clientY
            },
            s = function (t) {
                if (t.stopImmediatePropagation(), i.state.dragable) {
                    var e = t.clientX - i.state.mouseX,
                        a = t.clientY - i.state.mouseY,
                        o = n.css("background-position").split(" "),
                        s = e + parseInt(o[0]),
                        r = a + parseInt(o[1]);
                    n.css("background-position", s + "px " + r + "px"), i.state.mouseX = t.clientX, i.state.mouseY = t.clientY
                }
            },
            r = function (t) {
                t.stopImmediatePropagation(), i.state.dragable = false
            },
            u = function (t) {

                i.ratio *= t.originalEvent.wheelDelta > 0 || t.originalEvent.detail < 0 ? 1.1 : .9, a()
            };
        return i.spinner.show(), i.image.onload = function () {
            i.spinner.hide(), a(), n.bind("mousedown", o), n.bind("mousemove", s), n.bind("mouseup mouseout", r), n.bind("mousewheel DOMMouseScroll", u)
        }, i.image.src = e.imgSrc, n.on("remove", function () {
            t(window).unbind("mouseup", r)
        }), i
    };
    jQuery.fn.cropbox = function (t) {
        return new e(t, this)
    }
});

$(window).ready(function () {
    $('#btn_list').hide();
    var options = {
        thumbBox: '#thumbBox',
        spinner: '#spinner',
        imgSrc: ''
    }
    var cropper = $('#imageBox').cropbox(options);
    $('#picinfo').on('change', function () {
        $('#btn_list').show();
        var target = this;
        getOrientation(target.files[0], function (orientation) {
            var reader = new FileReader();
            reader.readAsDataURL(target.files[0]);
            reader.onload = function (evt) {
                var base64 = evt.target.result;
                // 將圖片旋轉到正確的角度
                resetOrientation(base64, orientation, function (resultBase64) {
                    //                            callback(resultBase64);
                    options.imgSrc = resultBase64;
                    cropper = $('#imageBox').cropbox(options);
                });
            }
        });
    })
    $('#btnCrop').on('click', function () {
        var img = cropper.getDataURL();
        $('#spinner').html('<img src="' + img + '" style="height:188px;width:188px;">');
        $('#hid_base64_pic').val(img);
    })
    $('#btnZoomIn').on('click', function () {
        cropper.zoomIn();
    })
    $('#btnZoomOut').on('click', function () {
        cropper.zoomOut();
    })
    $('#btnRotating').on('click', function () {
        var img = new Image();
        img.onload = function () {
            var width = img.width,
                height = img.height,
                canvas = document.createElement('canvas'),
                ctx = canvas.getContext("2d");
            canvas.width = height;
            canvas.height = width;

            ctx.transform(0, 1, -1, 0, height, 0);

            // draw image
            ctx.drawImage(img, 0, 0);
            // export base64
            cropper.image.src = canvas.toDataURL('image/png');
            cropper.updateImg();
        };
        var background_image = $('#imageBox').css("background-image");
        var re = /url\("(.*)"\)/;
        var srcBase64 = background_image.replace(re, '$1')
        img.src = srcBase64;
    })
    $('#btnFlip').on('click', function () {
        var img = new Image();
        img.onload = function () {
            var width = img.width,
                height = img.height,
                canvas = document.createElement('canvas'),
                ctx = canvas.getContext("2d");
            canvas.width = width;
            canvas.height = height;

            ctx.transform(-1, 0, 0, 1, width, 0);

            // draw image
            ctx.drawImage(img, 0, 0);
            // export base64
            cropper.image.src = canvas.toDataURL('image/png');
            cropper.updateImg();
        };
        var background_image = $('#imageBox').css("background-image");
        var re = /url\("(.*)"\)/;
        var srcBase64 = background_image.replace(re, '$1')
        img.src = srcBase64;
    })
});


var ac_last_touch_event = null;
var mouseEventTypes = {
    touchstart: "mousedown",
    touchmove: "mousemove",
    touchend: "mouseup"
};
//轉發手機的觸碰事件
for (var originalType in mouseEventTypes) {
    document.addEventListener(originalType, function (originalEvent) {
        var event = document.createEvent("MouseEvents");
        var touch = originalEvent.changedTouches[0];

        //2020 01 14 click事件被誤認為手機的觸碰事件，我已經很盡力調校了，如果有找到更好的處理方法，再協助處理，謝謝～
        //某個前輩留～
        if (ac_last_touch_event != null &&
            touch.screenX === ac_last_touch_event.screenX &&
            touch.screenY === ac_last_touch_event.screenY &&
            touch.clientX === ac_last_touch_event.clientX &&
            touch.clientY === ac_last_touch_event.clientY &&
            touch.ctrlKey === ac_last_touch_event.ctrlKey &&
            touch.altKey === ac_last_touch_event.altKey &&
            touch.shiftKey === ac_last_touch_event.shiftKey &&
            touch.metaKey === ac_last_touch_event.metaKey) {

            // it is click event and send mousedown and mouseup event;
            ac_last_touch_event = null;
            event.initMouseEvent('mousedown', true, true, window, 0, touch.screenX, touch.screenY, touch.clientX, touch.clientY, touch.ctrlKey, touch.altKey, touch.shiftKey, touch.metaKey, 0, null);
            originalEvent.target.dispatchEvent(event);
            event.initMouseEvent('mouseup', true, true, window, 0, touch.screenX, touch.screenY, touch.clientX, touch.clientY, touch.ctrlKey, touch.altKey, touch.shiftKey, touch.metaKey, 0, null);
            originalEvent.target.dispatchEvent(event);
        } else {
            ac_last_touch_event = touch;
            event.initMouseEvent(mouseEventTypes[originalEvent.type], true, true, window, 0, touch.screenX, touch.screenY, touch.clientX, touch.clientY, touch.ctrlKey, touch.altKey, touch.shiftKey, touch.metaKey, 0, null);
            originalEvent.target.dispatchEvent(event);
        }
    });
}

// 獲取圖片旋轉的角度
function getOrientation(file, callback) {
    var reader = new FileReader();
    reader.readAsArrayBuffer(file);
    reader.onload = function (e) {
        var view = new DataView(e.target.result);
        if (view.getUint16(0, false) != 0xFFD8)
            return callback(-2);
        var length = view.byteLength, offset = 2;
        while (offset < length) {
            var marker = view.getUint16(offset, false);
            offset += 2;
            if (marker == 0xFFE1) {
                if (view.getUint32(offset += 2, false) != 0x45786966)
                    return callback(-1);
                var little = view.getUint16(offset += 6, false) == 0x4949;
                offset += view.getUint32(offset + 4, little);
                var tags = view.getUint16(offset, little);
                offset += 2;
                for (var i = 0; i < tags; i++)
                    if (view.getUint16(offset + (i * 12), little) == 0x0112)
                        return callback(view.getUint16(offset + (i * 12) + 8, little));
            } else if ((marker & 0xFF00) != 0xFF00)
                break;
            else
                offset += view.getUint16(offset, false);
        }
        return callback(-1);
    };
}
// 將圖片旋轉到正確的角度
function resetOrientation(srcBase64, srcOrientation, callback) {
    var img = new Image();
    img.onload = function () {
        var width = img.width,
            height = img.height,
            canvas = document.createElement('canvas'),
            ctx = canvas.getContext("2d");
        if ([5, 6, 7, 8].indexOf(srcOrientation) > -1) {
            canvas.width = height;
            canvas.height = width;
        } else {
            canvas.width = width;
            canvas.height = height;
        }
        switch (srcOrientation) {
            case 2:
                ctx.transform(-1, 0, 0, 1, width, 0);
                break;
            case 3:
                ctx.transform(-1, 0, 0, -1, width, height);
                break;
            case 4:
                ctx.transform(1, 0, 0, -1, 0, height);
                break;
            case 5:
                ctx.transform(0, 1, 1, 0, 0, 0);
                break;
            case 6:
                ctx.transform(0, 1, -1, 0, height, 0);
                break;
            case 7:
                ctx.transform(0, -1, -1, 0, height, width);
                break;
            case 8:
                ctx.transform(0, -1, 1, 0, 0, width);
                break;
            default:
                ctx.transform(1, 0, 0, 1, 0, 0);
                break;
        }
        ctx.drawImage(img, 0, 0);
        callback(canvas.toDataURL('image/png'));
    };
    img.src = srcBase64;
}