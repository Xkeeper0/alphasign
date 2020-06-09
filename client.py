#!/usr/bin/python3
# client.py
# 
# A thin client to read text from stdin, send it bitwise, then listen for a response over stdout
from sys import stdin, stdout

import usb.core
import usb.util

def tx(dev):
    # Get the output data and send it bytewise to the USB device
    tx_header = bytearray([ord(c) for c in '\x00\x00\x00\x00\x00\x01Z00\x02AA'])

    tx_data = bytearray(stdin.buffer.read()) + b'\x04'

    for byte in tx_header + tx_data:
	print(byte)
	endpoint.write(chr(byte))

def rx(endpoint):
    # Read the return code for the TXT signal
    rx_header = bytearray([ord(c) for c in '\x00\x00\x00\x00\x00\x01Z00\x02BA\x04'])

    buf = []

    for byte in rx_header:
        print(byte)
        endpoint.write(chr(byte))

    endpoint.read(buf, 100)

    print(buf)


# find our device
dev = usb.core.find(idVendor=0x8765, idProduct=0x1234)

# was it found?
if dev is None:
    raise ValueError('Device not found')

# set the active configuration. With no arguments, the first
# configuration will be the active one
dev.set_configuration()

# get an endpoint instance
cfg = dev.get_active_configuration()
intf = cfg[(0,0)]

ep = usb.util.find_descriptor(
    intf,
    # match the first OUT endpoint
    custom_match = \
    lambda e: \
        usb.util.endpoint_direction(e.bEndpointAddress) == \
        usb.util.ENDPOINT_OUT)

assert ep is not None

# TODO: put this in a persistent run loop if desired; verify these functions actually work first :)

# Transmit the contents of stdin to the display (on Linux I had to hit ^D twice to send, recommend not hitting enter, tho I could probably use readline on stdin and strip the newline if that's better)
tx(ep)

# Read the current TEXT file on the display
rx(ep)

# outf = open("packet.txt", "r")
# out = outf.read()


# fdsfgs = bytearray(out, "ascii")
# 
# #test = b"1234567890"
# 
# for x in fdsfgs:
#     print(x)
#     ep.write(chr(x))
#     ep.read(0)
