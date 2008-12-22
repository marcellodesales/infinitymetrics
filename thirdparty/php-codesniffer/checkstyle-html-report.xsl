<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" indent="yes"/>
    <xsl:decimal-format decimal-separator="." grouping-separator="," />

 	<!-- Checkstyle XML Style Sheet by Stephane Bailliez <sbailliez@apache.org>         -->
    <!-- Adapted for PHP Phing by Marcello de Sales <marcello.sales@gmail.com>          -->
    <!--          Infinity Metrics https://www.infinitymetrics.net                      -->
 	<!-- Part of the Checkstyle distribution found at http://checkstyle.sourceforge.net -->
 	<!-- Usage (generates checkstyle_report.html):                                      -->
 	<!--    <checkstyle failonviolation="false" config="${check.config}">               -->
	<!--      <fileset dir="${src.dir}" includes="**/*.java"/>                          -->
 	<!--      <formatter type="xml" toFile="${doc.dir}/checkstyle_report.xml"/>         -->
 	<!--    </checkstyle>                                                               -->
 	<!--    <style basedir="${doc.dir}" destdir="${doc.dir}"                            -->
 	<!--            includes="checkstyle_report.xml"                                    -->
 	<!--            style="${doc.dir}/checkstyle-noframes-sorted.xsl"/>                 -->

    <xsl:template match="checkstyle">
        <html>
            <head>
                <style type="text/css">
 	    .bannercell {
 	      border: 0px;
 	      padding: 0px;
 	    }
 	    body {
 	      margin-left: ;
 	      margin-right: ;
 	      font:normal 80% arial,helvetica,sanserif;
 	      background-color:#FFFFFF;
 	      color:#000000;
 	    }
 	    .a td {
 	      background: #efefef;
 	    }
 	    .b td {
 	      background: #fff;
 	    }
	    .warning td {
		  color:orange;
	    }
	    .error td {
		  color:red;
 	    }
 	    th, td {
 	      text-align: left;
	      vertical-align: top;
 	    }
 	    th {
 	      font-weight:bold;
 	      background: #ccc;
 	      color: black;
 	    }
 	    table, th, td {
 	      font-size: 100%;
 	      border: none;
        }

        td.warning {
            color: green;
        }

 	    table.log tr td, tr th {

 	    }
 	    h2 {
 	      font-weight:bold;
 	      font-size:140%;
 	      margin-bottom: 5;
 	    }
 	    h3 {
 	      font-size:100%;
 	      font-weight:bold;
 	      background: #525D76;
 	      color: white;
 	      text-decoration: none;
 	      padding: 5px;
 	      margin-right: 2px;
 	      margin-left: 2px;
 	      margin-bottom: 0;
 	    }
                </style>
            </head>
            <body>
                <a name="top"></a>
 	      <!-- jakarta logo -->
                <table border="0" cellpadding="0" cellspacing="0" width="0%">
                    <tr>
                        <td class="bannercell" rowspan="2">
 	          <!--a href="http://jakarta.apache.org/">
	          <img src="http://jakarta.apache.org/images/jakarta-logo.gif" alt="http://jakarta.apache.org" align="left" border="0"/>
 	          </a-->
                        </td>
                        <td class="text-align:right">
                            <h2>Infinity Metrics CheckStyle Audit</h2>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-align:right">Designed for use with
                            <a href='http://checkstyle.sourceforge.net/'>CheckStyle</a>,
                            <a href='http://jakarta.apache.org'>Ant</a>, 
			          <a href='http://pear.php.net/manual/en/package.php.php-codesniffer.php'>Php Code Sniffer</a> and 
				    <a href='http://phing.info/trac/'>Phing</a>.
                        </td>
                    </tr>
                </table>
                <hr size="1"/>

 	            <!-- Summary part -->
                <xsl:apply-templates select="." mode="summary"/>
                <hr size="1" width="0%" align="left"/>

	            <!-- Package List part -->
                <xsl:apply-templates select="." mode="filelist"/>
                <hr size="1" width="0%" align="left"/>

	            <!-- For each package create its part -->
                <xsl:for-each select="file">
                    <xsl:sort select="@name"/>
                    <xsl:apply-templates select="."/>
                    <p/>
                    <p/>
                </xsl:for-each>
                <hr size="1" width="0%" align="left"/>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="checkstyle" mode="filelist">
        <h3>PHP Artifacts</h3>
        <table class="log" border="0" cellpadding="5" cellspacing="2" width="0%">
            <tr>
                <th>File Path</th>
                <th><img src="../../../../thirdparty/php-codesniffer/error.png" alt="Errors in code style" />Errors</th>
                <th><img src="../../../../thirdparty/php-codesniffer/warning.png" alt="Warnings in code style" />Warnings</th>
            </tr>
            <xsl:for-each select="file">
                <xsl:sort data-type="number" order="descending" select="count(error)"/>
                <xsl:variable name="errorCount" select="count(error[@severity='error'])"/>
                <xsl:variable name="warningCount" select="count(error[@severity='warning'])"/>
                <tr>
                    <xsl:call-template name="alternated-row"/>
                    <td>
                        <a href="file:///{@name}">
                            <xsl:value-of select="@name"/>
                        </a>
                    </td>
                    <td>
                        <xsl:value-of select="$errorCount"/>
                    </td>
                    <td>
                        <xsl:value-of select="$warningCount"/>
                    </td>
                </tr>
            </xsl:for-each>
        </table>
    </xsl:template>
        
    <xsl:template match="file">
        <a name="{@name}"></a>
        <h3>File
            <xsl:value-of select="@name"/>
        </h3>

        <table class="log" border="0" cellpadding="5" cellspacing="2" width="0%">
            <tr>
                <th>Type</th>
                <th>Problem</th>
                <th>Line</th>
            </tr>
            <xsl:for-each select="error">
                <tr>
                    <xsl:call-template name="alternated-row">
                        <xsl:with-param name="errorType"><xsl:value-of select="@severity"/></xsl:with-param>
                    </xsl:call-template>
                    <td>									
						<xsl:choose>
						  <xsl:when test="@severity = 'warning'">
							  <img alt="Warning" src="../../../../thirdparty/php-codesniffer/warning.png"/>
						  </xsl:when>
						  <xsl:otherwise>
								<img alt="Error" src="../../../../thirdparty/php-codesniffer/error.png"/>
						  </xsl:otherwise>
						</xsl:choose>
                    </td>
                    <td>
                        <xsl:value-of select="@message"/>
                    </td>
                    <td>
                        <xsl:value-of select="@line"/>
                    </td>
                </tr>
            </xsl:for-each>
        </table>
        <a href="#top">Back to top</a>
    </xsl:template>

    <xsl:template match="checkstyle" mode="summary">
        <h3>Summary</h3>
        <xsl:variable name="fileCount" select="count(file)"/>
        <xsl:variable name="errorCount" select="count(file/error[@severity='error'])"/>
        <xsl:variable name="warningCount" select="count(file/error[@severity='warning'])"/>
        <table class="log" border="0" cellpadding="5" cellspacing="2" width="0%">
            <tr>
                <th>Files</th>
                <th>Errors</th>
                <th>Warning</th>
            </tr>
            <tr>
                <xsl:call-template name="alternated-row">
                    <xsl:with-param name="errorType"><xsl:value-of select="file/error[@severity]"/></xsl:with-param>
                </xsl:call-template>
                <td>
                    <xsl:value-of select="$fileCount"/>
                </td>
                <td>
                    <xsl:value-of select="$errorCount"/>
                </td>
                <td>
                    <xsl:value-of select="$warningCount"/>
                </td>
            </tr>
        </table>
    </xsl:template>

    <xsl:template name="alternated-row">
        <xsl:param name="errorType" />
        <xsl:choose>
          <xsl:when test="$errorType = 'warning'">
            <xsl:attribute name="class">
                <xsl:if test="position() mod 2 = 1">a warning</xsl:if>
                <xsl:if test="position() mod 2 = 0">b warning</xsl:if>
            </xsl:attribute>
          </xsl:when>
          <xsl:otherwise>
            <xsl:attribute name="class">
                <xsl:if test="position() mod 2 = 1">a error</xsl:if>
                <xsl:if test="position() mod 2 = 0">b error</xsl:if>
            </xsl:attribute>
          </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
</xsl:stylesheet>
