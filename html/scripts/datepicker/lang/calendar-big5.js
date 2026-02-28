// ** I18N

// Calendar big5 language
// Author: Gary Fu, <gary@garyfu.idv.tw>
// Encoding: big5
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.
	
// full day names
Calendar._DN = new Array
("¬P´dé",
 "¬P´d@",
 "¬P´dG",
 "¬P´dT",
 "¬P´e|",
 "¬P´d­",
 "¬P´d»",
 "¬P´dé");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("¤é",
 "¤@",
 "¤G",
 "¤T",
 "¥|",
 "¤­",
 "¤»",
 "¤é");

// full month names
Calendar._MN = new Array
("¤@¤ë",
 "¤G¤ë",
 "¤T¤ë",
 "¥|¤ë",
 "¤­¤ë",
 "¤»¤ë",
 "¤C¤ë",
 "¤K¤ë",
 "¤E¤ë",
 "¤Q¤ë",
 "¤Q¤@¤ë",
 "¤Q¤G¤ë");

// short month names
Calendar._SMN = new Array
("¤@¤ë",
 "¤G¤ë",
 "¤T¤ë",
 "¥|¤ë",
 "¤­¤ë",
 "¤»¤ë",
 "¤C¤ë",
 "¤K¤ë",
 "¤E¤ë",
 "¤Q¤ë",
 "¤Q¤@¤ë",
 "¤Q¤G¤ë");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Ãö©ó";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"¤é´ï¾ܤèªk:\n" +
"- ¨ϥÎ \xab, \xbb «ö¶s¥i¿ï¾ܦ~¥÷\n" +
"- ¨ϥÎ " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " «ö¶s¥i¿ï¾ܤë¥÷\n" +
"- «ö¦í¤W­±ªº«ö¶s¥i¥H¥[§ֿï¨ú";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"®ɶ¡¿ï¾ܤèªk:\n" +
"- ÂI;¥ô¦óªº®ɶ¡³¡¥÷¥i¼W¥[¨ä­È\n" +
"- ¦P®ɫöShiftÁä¦AÂI;¥i´î¤֨ä­È\n" +
"- ÂI;¨éì¦²¥i¥[§֧ïÅܪº­È";

Calendar._TT["PREV_YEAR"] = "¤W¤@¦~ («ö¦í¿ï³æ)";
Calendar._TT["PREV_MONTH"] = "¤U¤@¦~ («ö¦í¿ï³æ)";
Calendar._TT["GO_TODAY"] = "¨ì¤µ¤é";
Calendar._TT["NEXT_MONTH"] = "¤W¤@¤ë («ö¦í¿ï³æ)";
Calendar._TT["NEXT_YEAR"] = "¤U¤@¤ë («ö¦í¿ï³æ)";
Calendar._TT["SEL_DATE"] = "¿ï¾ܤé´Á";
Calendar._TT["DRAG_TO_MOVE"] = "©ì¦²";
Calendar._TT["PART_TODAY"] = " (¤µ¤é)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "±N %s Åã¥ܦb«e";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Ãö³¬";
Calendar._TT["TODAY"] = "¤µ¤é";
Calendar._TT["TIME_PART"] = "ÂI;or©ì¦²¥i§ïÅܮɶ¡(¦P®ɫöShift¬°´î)";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "¶g";
Calendar._TT["TIME"] = "Time:";
