# New ports collection makefile for:	FreshPorts
# Date created:		01 March 2001
# Whom:			John P. Campbell <jpcampbell@bigfoot.com>
#
# $FreeBSD$
#

PORTNAME=	FreshPorts
PORTVERSION=	0.3b
CATEGORIES=	misc
MASTER_SITES=	http://www.FreshPorts.org/distfiles/
DISTNAME=	${PORTNAME}-${PORTVERSION}

MAINTAINER=	ports@freebsddiary.org

RUN_DEPENDS=	${LOCALBASE}/lib/perl5/site_perl/${PERL_VER}/LWP.pm:${PORTSDIR}/www/p5-libwww

MAN1=	freshports.1

do-install:
	${INSTALL_SCRIPT}	${WRKDIR}/${DISTNAME}/freshports ${LOCALBASE}/bin
	${INSTALL_MAN} ${WRKSRC}/freshports.1 ${PREFIX}/man/man1

.include <bsd.port.mk>
