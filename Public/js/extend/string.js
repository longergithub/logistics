/*!
 * String Extensions 2.0.118
 *
!*/
(function()
{
    "use strict";
    
    var $S = String,
        $P = $S.prototype;

    /**
    * [静态方法] 将指定的 String 中的每个格式项替换为相应对象的值的文本等效项。
    * @param {String} format 必选项，复合格式字符串。
    * @param {Array} arguments 可选项，包含零个或多个要格式化的对象的 Object 数组。
    * @return {String} format 的一个副本，其中格式项已替换为 @args 中相应 Object 实例的 String 等效项。
    **/
    $S.format = function()
    {
        var array = [].slice.apply(arguments),
            source = array.shift();

        return source.replace(/\{[0-9]+\}/g, function(w){return array[w.slice(1,-1)];});
    };

    /**
     * [静态方法] 指示指定的 String 对象是 null 还是 Empty 字符串。
     * @param {String｝ value 必选项，一个 String 引用。
     * @return {Boolean} 如果 value 参数为 null 或空字符串 ("")，则为 true；否则为 false。
     **/
    $S.isNullOrEmpty = function(value)
    {
        return !value || typeof value === "undefined" || value === String.empty;
    };

    /**
     * [静态方法] 用一些字符替换所有指定的 String 对象的另一些字符。
     * @param {String｝ value 必选项，一个 String 引用。
     * @param {String｝ substr 必选项，规定被替换的子字符串。
     * @param {String｝ replacement 必选项，一个 String 值。规定了替换文本或生成替换文本的函数。
     * @return {String} 替换后的字符串。
     **/
    $S.replaceAll = function(value, substr, replacement)
    {
        if(value != null)
            value = value.replace(new RegExp(substr,"gm"), replacement)

        return value;
    };

    /**
    * [静态方法] 生成一个 GUID 字符串实例。
    * @return {String} 新的 Guid 字符串。
    **/
    $S.newGuid = function(hasSeparator)
    {
        var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz".split("");
        var buffer = [];
        var random = 0,
            separator = hasSeparator ? "-" : "";

        buffer[8] = buffer[13] = buffer[18] = buffer[23] = separator;
        buffer[14] = "4";

        for(var i = 0; i < 36; i++)
        {
            if(!buffer[i])
            {
                random = 0 | Math.random() * 16;

                buffer[i] = chars[(i == 19) ? (random & 0x3) | 0x8 : random];
            }
        }
        return buffer.join("");
    };

    /**
    * [实例方法] 返回字符串的片段。
    * @param {Number} index 必选项，范围开始处的从零开始的索引。
    * @param {Number} count 必选项，范围中的元素数。
    * @return {String} 返回指定范围的字符串片段。
    **/
    $P.getRange = function(index, count)
    {
        return this.slice.call(this, index, count);
    };

    /**
    * [实例方法] 从当前 String 对象移除所有前导空白字符和尾部空白字符。 
    * @return {String} 从当前 String 对象的开始和末尾移除所有空白字符后保留的字符串。
    **/
    $P.trim = function()
    {
        var result = this,
            result = result.replace(/^\s\s*/, ''),
            reg = /\s/,
            i = result.length;

        while(reg.test(result.charAt(--i)));

        return result.getRange(0, i + 1);
    };
}());